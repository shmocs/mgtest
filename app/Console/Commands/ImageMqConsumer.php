<?php

namespace App\Console\Commands;

use App\Extensions\AmqpConnectionChannel;
use App\Photo;
use App\Services\ImportService;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ImageMqConsumer extends Command
{
    use AmqpConnectionChannel;

    protected $signature = 'imagemq:consumer';

    protected $description = 'RabbitMQ direct consumer';

    public function handle (ImportService $import) {

        $qn = 'image.queue';
        $ex = 'image.exchange';

        /* @var AMQPStreamConnection $connection */
        /* @var \PhpAmqpLib\Channel\AMQPChannel $channel */
        [ $connection, $channel ] = $this->setup();

        $channel->exchange_declare($ex, 'direct', false, true, false);
        $channel->queue_declare($qn, false, true, false, false);
        $channel->queue_bind($qn, $ex);

        $callback = function (AMQPMessage $msg) use ($qn, $import) {

            // make sure resource is present in Photo table
            $image = Photo::where([
                'processed' => 0,
                'url' => $msg->body
            ])->get()->first();

            if (!$image) {
                $this->error("Inexistent image url or already processed/cached");
            } else {
                // cache image
                $response = $import->cacheImage($image);

                if ($response['type'] && $response['type'] == 'success') {
                    $this->info(sprintf("Processed [queue: %s] - [MSG: %s] - ok.", $qn, $msg->body));
                    //$msg->ack();
                } else {
                    $this->warn(sprintf("[queue: %s] - [MSG: %s] - %s", $qn, $msg->body, $response['msg']));
                    //$msg->nack();
                }
            }
        };

        //$channel->basic_qos(null, 1, null);
        $channel->basic_consume($qn, '', false, true, false, false, $callback);

        $this->output->warning('Waiting for messages [' . $qn . ']');

        while ( $channel->is_consuming() ) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
