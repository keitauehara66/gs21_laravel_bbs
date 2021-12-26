<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Post;
use App\Category;
use Illuminate\Support\Str;
// 写真のリサイズのために入れたが、動画には使えず、むしろエラーになるので使いづらい
use \InterventionImage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = \Request::query();
        $posts = Post::latest()->paginate(3);
        $posts->load('category', 'user');

        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = new Category;
        $categories = $category->getLists()->prepend('選択', '');
        
        return view('posts.create', ['categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        if(is_null($request->image)){
            $post = new Post;
            $post->user_id = $request->user_id;
            $post->category_id = $request->category_id;
            $post->title = $request->title;
            $post->content = $request->content;

            $post->save();

        }elseif($request->file('image')->isValid()){
            $post = new Post;
            $post->user_id = $request->user_id;
            $post->category_id = $request->category_id;
            $post->title = $request->title;
            $post->content = $request->content;

            $filename = $request->file('image')->store('public/image');
            $file = $request->file('image');
            //アスペクト比を維持、画像サイズを横幅360pxにして保存する。
            // InterventionImage::make($file)->resize(360, null, function ($constraint) {$constraint->aspectRatio();})->save(storage_path('app/'.$filename));

            $post->image = basename($filename);

            $post->save();
        }
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post->load('category', 'user', 'comments.user');
        return view('posts.show', [
            'post' => $post,
        ]);
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

    public function search(Request $request)
    {
        $posts = Post::where('title', 'like', "%{$request->search}%")
                    ->orwhere('content', 'like', "%{$request->search}%")
                    ->paginate(3);

        $search_result = $request->search.'の検索結果'.$posts->total().'件';

        return view('posts.index', [
            'posts' => $posts,
            'search_result' => $search_result,
            'search_query' => $request->search,
        ]);
    }

}
