<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index($id) {
        try {
            $movie = Movie::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Page not found');
        }

        return view('movie')->withMovie($movie);
    }

    public function showAll() {
        return view('movies')->withMovies(Movie::all());
    }

    public function storeMovie() {
        $r = request();

        $this->validate($r, [
            'headline' => 'required',
            'body' => 'required'
        ]);

        $movie = Movie::create([
            'body' => request()->body,
            'cert' => request()->cert,
            'class' => request()->class,
            'duration' => request()->duration,
            'headline' => request()->headline,
            'movie_id' => request()->movie_id,
            'last_updated' => request()->last_updated,
            'quote' => request()->quote,
            'rating' => request()->rating,
            'sum' => request()->sum,
            'synopsis' => request()->synopsis,
            'url' => request()->url,
            'year' => request()->year,
        ]);
    }
}
