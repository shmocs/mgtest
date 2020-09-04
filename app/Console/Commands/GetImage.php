<?php

namespace App\Console\Commands;

use App\Photo;
use App\Services\ImportService;
use Illuminate\Console\Command;

class GetImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getimage {--url=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The disk path to cache/save the downloaded image.
     *
     * @var string
     */
    protected $cachePath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ImportService $import)
    {
        $url = $this->option('url');
        //$path = $this->cachePath . '/' . $url;
        $this->info("Caching image [$url] ...");

        try {
            // make sure resource is present in Photo table
            $image = Photo::where([
                'processed' => 0,
                'url' => $url
            ])->get()->first();

            if (!$image) {
                $this->error("Inexistent image url or already processed/cached");
            } else {
                // cache image
                $response = $import->cacheImage($image);
                if ($response['type'] && $response['type'] == 'success') {
                    $this->line("ok.");
                } else {
                    $this->error( $response['msg']);
                }
            }

        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return 0;
    }
}
