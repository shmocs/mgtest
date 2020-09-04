<?php

namespace App\Console\Commands;

use App\Services\ImportService;
use Illuminate\Console\Command;

class ParseJsonFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $feedUrl = config('mindgeek.feedUrl');

        $this->info("Getting json contents from [$feedUrl] ....");

        $file = file_get_contents($feedUrl);
        $items = json_decode($file, false, 512, JSON_THROW_ON_ERROR|JSON_INVALID_UTF8_SUBSTITUTE);

        $processed = 0;
        foreach ($items as $item) {

            $movie = $import->createMovie([
                'body' => $item->body,
                'cert' => $item->cert,
                'class' => $item->class,
                'duration' => $item->duration,
                'headline' => $item->headline,
                'movie_id' => $item->id,
                'last_updated' => $item->lastUpdated,
                'quote' => $item->quote ?? null,
                'rating' => $item->rating ?? null,
                'review_author' => $item->reviewAuthor ?? null,
                'sky_go_id' => $item->skyGoId ?? null,
                'sky_go_url' => $item->skyGoUrl ?? null,
                'sum' => $item->sum,
                'synopsis' => $item->synopsis,
                'url' => $item->url,
                'vw_title' => $item->viewingWindow->title ?? null,
                'vw_start_date' => $item->viewingWindow->startDate ?? null,
                'vw_way_to_watch' => $item->viewingWindow->wayToWatch ?? null,
                'vw_end_date' => $item->viewingWindow->endDate ?? null,
                'year' => $item->year,
            ]);

            if ($movie->id) {
                $processed++;
                $import->createActors($movie->id, $item->cast);
                $import->createGenres($movie->id, $item->genres ?? []);
                $import->createDirectors($movie->id, $item->directors);
                $import->createImages($movie->id, $item->cardImages, 'card');
                $import->createImages($movie->id, $item->keyArtImages, 'key_art');
                $import->createVideos($movie->id, $item->videos ?? []);
                $this->info("Processed item [$processed] [$movie->headline]");
            }
        }

        // call imagemq:publisher to start enqueueing images for cache processing
        $this->call('imagemq:publisher');

        return 0;
    }
}
