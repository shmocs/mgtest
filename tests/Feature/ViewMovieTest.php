<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Movie;

class ViewMovieTest extends TestCase
{
    use RefreshDatabase;


    /**
     * @group found
     */
    public function testCanViewMovie() {

        // Arrangement - prepare DB/stuff before test runs
        // create a movie item
        $movie = factory(Movie::class)->create();

        // Action
        // visite a route
        $resp = $this->get("/movie/{$movie->id}");

        // Assert
        // status code 200
        // we can see the movie title
        // we can see the movie desctiption
        $resp->assertStatus(200);
        $resp->assertSee($movie->headline);
        $resp->assertSee($movie->body);
    }

    /**
     * @group found
     */
    public function testCanViewAllMovies() {
        $this->withoutExceptionHandling();

        // Arrangement - prepare DB/stuff before test runs (create some movies)
        $movie1 = factory(Movie::class)->create();
        $movie2 = factory(Movie::class)->create();

        // Action - visit movies listing route
        $resp = $this->get("/movies");

        // Assert - 200 status + we detect needed text in the page
        $resp->assertStatus(200);
        $resp->assertSee($movie1->headline);
        $resp->assertSee($movie2->headline);
        $resp->assertSee(Str::limit($movie1->body, 400));
        $resp->assertSee(Str::limit($movie2->body, 400));
    }



    /**
     * @group not-found
     */
    public function testShow404WhenMovieNotFound() {
        $resp = $this->get("/movie/random_id");

        $resp->assertStatus(404);
        $resp->assertSee('The movie you are looking for could not be found');
    }
}
