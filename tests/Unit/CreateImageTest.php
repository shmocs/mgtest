<?php

namespace Tests\Unit;

use App\Movie;
use App\Photo;
use App\Services\ImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateImageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group create
     */
    public function testCreateImage() {
        $this->withoutExceptionHandling();

        // arrangement
        $fakeImage = factory(Photo::class)->make();
        $fakePostData = $fakeImage->attributesToArray();
        $fakePostData['url'] = 'https://testurl';

        // action
        $response = $this->post('/store-image', $fakePostData);

        // assert - that DB contains the new created image
        $this->assertDatabaseHas('photo', $fakePostData);

        $image = Photo::where(['url' => 'https://testurl'])->first();
        $this->assertEquals('https://testurl', $image->url);

    }

    /**
     * @group cache
     */
    public function testCacheFakeImageLocal() {

        // arrangement
        $fakeImage = factory(Photo::class)->create();
        Storage::fake(config('filesystems.default'));


        // action
        $fileName = 'test.png';
        $image = UploadedFile::fake()->image($fileName);
        $image->storePubliclyAs('images', $fileName);


        // assert - that DB file exists
        Storage::disk(config('filesystems.default'))->assertExists(
            'images/' . $fakeImage->cached_file
        );

    }

    /**
     * @group cache
     */
    public function testCacheImageSuccess() {

        // arrangement
        $fakeImage = factory(Photo::class)->create();
        // example of failing image
        $fakeImage->url = 'https://mgtechtest.blob.core.windows.net/images/unscaled/2012/04/04/funny-games-1997-1S-KA-to-KP3.jpg';

        // action - cache image from URL
        $import = new ImportService();
        $response = $import->cacheImage($fakeImage);

        // assert - that for known existing image - we get success
        $this->assertEquals($response['type'], 'success');
    }

    /**
     * @group cache
     */
    public function testCacheImageFail() {

        // arrangement
        $fakeImage = factory(Photo::class)->create();
        // example of failing image
        $fakeImage->url = 'https://mgtechtest.blob.core.windows.net/images/unscaled/2014/04/15/Pacific-Rim-04-DI-DI-to-LP3.jpg';

        // action - cache image from URL
        $import = new ImportService();
        $response = $import->cacheImage($fakeImage);

        // assert - that for known missing image - we get error
        $this->assertEquals($response['type'], 'error');
    }

}
