<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'movie';
    protected $guarded = [];

    public function createdAt() {
        return $this->created_at->toFormattedDateString();
    }

    public function cardImages() {
        return $this->hasMany(Photo::class, 'movie_id', 'id')->where('scope', '=', 'card');
    }

    public function mainThumb() {
        return $this->hasOne(Photo::class, 'movie_id', 'id')
            ->where('scope', '=', 'card');
            //->where('cached_file', 'like', '%LP3.jpg');
    }

    public function keyArtImages() {
        return $this->hasMany(Photo::class, 'movie_id', 'id')->where('scope', '=', 'key_art');
    }

    public function directors() {
        return $this->belongsToMany(Director::class, 'movie_director');
    }

    public function actors() {
        return $this->belongsToMany(Actor::class, 'movie_actor');
    }

    public function genres() {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }

    public function videos() {
        return $this->hasMany(Video::class);
    }

    public function trailer() {
        return $this->hasOne(Video::class)->where('type', '=', 'trailer');
    }

}
