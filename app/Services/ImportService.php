<?php


namespace App\Services;


use App\Director;
use App\Genre;
use App\Movie;
use App\Actor;
use App\MovieActor;
use App\MovieDirector;
use App\MovieGenre;
use App\Photo;
use App\Video;
use App\VideoAlternative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportService
{
    public $cachePath;

    public function __construct()
    {
        $this->cachePath = config('mindgeek.cache_images_path');
    }

    public function createMovie($movie)
    {
        return Movie::firstOrCreate($movie);
    }

    public function createActors($movie_id, $actors)
    {
        foreach ($actors as $actorObj) {

            $actor = Actor::firstOrCreate(['name' => $actorObj->name]);
            MovieActor::firstOrCreate(['movie_id' => $movie_id, 'actor_id' => $actor->id]);
        }
    }

    public function createGenres($movie_id, $genres)
    {
        foreach ($genres as $genreData) {

            $genre = Genre::firstOrCreate(['name' => $genreData]);
            MovieGenre::firstOrCreate(['movie_id' => $movie_id, 'genre_id' => $genre->id]);
        }
    }

    public function createDirectors($movie_id, $directors)
    {
        foreach ($directors as $directorObj) {

            $director = Director::firstOrCreate(['name' => $directorObj->name]);
            MovieDirector::firstOrCreate(['movie_id' => $movie_id, 'director_id' => $director->id]);
        }
    }

    public function createImages($movie_id, $images, $scope)
    {
        foreach ($images as $photoData) {
            Photo::firstOrCreate([
                'movie_id' => $movie_id,
                'scope' => $scope,
                'url' => $photoData->url,
                'processed' => 0,
                'w' => $photoData->w,
                'h' => $photoData->h,
            ]);
        }
    }


    public function createVideos($movie_id, $videos)
    {
        foreach ($videos as $videoData) {
            $video = Video::firstOrCreate([
                'movie_id' => $movie_id,
                'title' => $videoData->title,
                'type' => $videoData->type,
                'thumbnail_url' => $videoData->thumbnailUrl ?? null,
                'url' => $videoData->url,
            ]);
            foreach ($videoData->alternatives ?? [] as $alternative) {
                VideoAlternative::firstOrCreate([
                    'video_id' => $video->id,
                    'quality' => $alternative->quality,
                    'url' => $alternative->url,
                ]);
            }
        }
    }

    /**
     * For a given Photo object - try to cache/download locally its image from URL
     *
     * @param Photo $image
     * @return string[]
     */
    public function cacheImage(Photo $image) {

        $response = [
            'type' => 'success',
            'msg' => '',
        ];

        // extracted basename & set path to store cached image
        $path = parse_url($image->url, PHP_URL_PATH);
        $fileName = basename($path);
        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();


        // get the image from url
        try {
            $copied = copy($image->url, $storagePath . $this->cachePath . '/' . $fileName);
            if ($copied) {
                $image->cached_file = $fileName;
            }
        } catch (\Throwable $e) {
            $response['type'] = 'error';
            $response['msg'] = $e->getMessage();
        }

        $image->processed = 1; // ensure we process only once the resource
        $image->save();


        return $response;
    }

}
