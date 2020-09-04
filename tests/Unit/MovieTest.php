<?php

namespace Tests\Unit;

use App\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group formatted-date
     */
    public function testCanGetCreatedAtFormattedDate()
    {
        // arrangement - create a video
        $movie = factory(Movie::class)->create();

        // action - get the value by calling a method
        $formattedDate = $movie->createdAt();

        // asssert - returned value is as expected
        $this->assertEquals($movie->created_at->toFormattedDateString(), $formattedDate);
    }
}
