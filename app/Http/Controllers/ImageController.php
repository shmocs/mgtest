<?php

namespace App\Http\Controllers;

use App\Photo;
use Illuminate\Http\Request;

class ImageController extends Controller
{

    public function storeImage() {
        $r = request();

        $this->validate($r, [
            'url' => 'required',
        ]);

        $image = Photo::create([
            'movie_id' => request()->movie_id,
            'scope' => request()->scope,
            'processed' => request()->processed,
            'url' => request()->url,
            'w' => request()->w,
            'h' => request()->h,
        ]);
    }
}
