<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Comment;
use App\Post;
use App\Category;
use Illuminate\Support\Str;
// 写真のリサイズのために入れたが、動画には使えず、むしろエラーになるので使いづらい
use \InterventionImage;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $q = \Request::query();
        
        return view('comments.create', [
            'post_id' => $q['post_id'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request)
    {
        if(is_null($request->image)){
            $comment = new Comment;
            $comment->user_id = $request->user_id;
            $comment->post_id = $request->post_id;
            $comment->comment = $request->comment;

            $comment->save();

        }elseif($request->file('image')->isValid()){
            $comment = new Comment;
            $comment->user_id = $request->user_id;
            $comment->post_id = $request->post_id;
            $comment->comment = $request->comment;

            $filename = $request->file('image')->store('public/image');
            $file = $request->file('image');
            //アスペクト比を維持、画像サイズを横幅360pxにして保存する。
            // InterventionImage::make($file)->resize(360, null, function ($constraint) {$constraint->aspectRatio();})->save(storage_path('app/'.$filename));

            $comment->image = basename($filename);

            $comment->save();
        }
        return redirect('/posts/'.$comment->post_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
