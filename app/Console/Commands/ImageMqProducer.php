<?php

namespace App\Console\Commands;


use App\Extensions\AmqpConnectionChannel;
use App\Photo;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ImageMqProducer extends Command
{
    use AmqpConnectionChannel;

    protected $signature = 'imagemq:publisher {--limit=}';

    protected $description = 'RabbitMQ direct producer';

    public function handle () {
        $qn = 'image.queue';
        $ex = 'image.exchange';

        $limit = $this->option('limit') ?? 99999;

        $images = Photo::where(['processed' => 0])
            ->take($limit)
            ->get();

        if ($images->count() == 0) {
            $this->error("No image to enqueue.");
        } else {

            /* @var AMQPStreamConnection $connection */
            /* @var \PhpAmqpLib\Channel\AMQPChannel $channel */
            [$connection, $channel] = $this->setup();

            $channel->exchange_declare($ex, 'direct', false, true, false);
            $channel->queue_declare($qn, false, true, false, false, false);
            $channel->queue_bind($qn, $ex);

            foreach ($images as $image) {
                $message = new AMQPMessage($image->url, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

                $channel->basic_publish($message, $ex);

                $this->info(sprintf('Sent message: [queue: %s] - [MSG: %s]', $qn, $image->url));
            }

            $channel->close();
            $connection->close();
        }

    }
}
