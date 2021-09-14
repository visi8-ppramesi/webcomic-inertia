<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;

class FileController extends Controller
{
    public function fetchPageImage(Request $request, $filename){
        if(! $request->hasValidSignature()){
            abort(401);
        }
        $path = public_path(config('comic.comic_path') . $filename);
        return Image::make($path)->response();
    }
}
