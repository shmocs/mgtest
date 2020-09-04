<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'video';
    protected $guarded = [];

    public function alternatives() {
        return $this->hasMany(VideoAlternative::class);
    }
}
