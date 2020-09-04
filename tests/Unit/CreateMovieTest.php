<?php

namespace Tests\Unit;

use App\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateMovieTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group create
     */
    public function testCreateMovie() {
        $this->withoutExceptionHandling();

        // arrangement
        $fakeMovie = factory(Movie::class)->make();
        $fakePostData = $fakeMovie->attributesToArray();
        $fakePostData['headline'] = 'test headline';

        // action
        $response = $this->post('/store-movie', $fakePostData);

        // assert - that DB contains the new created movie
        $this->assertDatabaseHas('movie', $fakePostData);

        $movie = Movie::find(1);
        $this->assertEquals('test headline', $movie->headline);

    }

    /**
     * @group create
     */
    public function testHeadlineIsRequiredToCreateMovie() {

        // arrangement
        $fakeMovie = factory(Movie::class)->make();
        $fakePostData = $fakeMovie->attributesToArray();
        $fakePostData['headline'] = null;

        // action
        $response = $this->post('/store-movie', $fakePostData);

        // assert - that we have the error validation for headline missing
        $response->assertSessionHasErrors('headline');

    }

    /**
     * @group create
     */
    public function testBodyIsRequiredToCreateMovie() {

        // arrangement
        $fakeMovie = factory(Movie::class)->make();
        $fakePostData = $fakeMovie->attributesToArray();
        $fakePostData['body'] = null;

        // action
        $response = $this->post('/store-movie', $fakePostData);

        // assert - that we have the error validation for body missing
        $response->assertSessionHasErrors('body');

    }
}
