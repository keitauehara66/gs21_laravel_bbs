<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CameraCaptureController extends Controller
{
    public function create() {

        return view('camera_capture');

    }

    public function store(Request $request) {

        $request->validate([
            'image' => ['required', 'file', 'image']
        ]);

        $result = false;

        try {

            $request->file('image')->store('cropped_images'); // storage/app/cropped_images フォルダへ保存
            $result = true;

        } catch (\Exception $e) {

            // エラーの場合

        }

        return [
            'result' => $result
        ];

    }
}
