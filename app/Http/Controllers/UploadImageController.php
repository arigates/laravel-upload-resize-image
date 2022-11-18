<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    public function form(): Renderable
    {
        return view('upload');
    }

    public function submit(Request $request): JsonResponse
    {
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {
                $name = time().rand(1,100000000).'.'.$image->extension();
                Storage::disk('public')->putFileAs('images', $image, $name);
            }

            return response()->json("ok");
        }

        return response()->json("image required");
    }
}
