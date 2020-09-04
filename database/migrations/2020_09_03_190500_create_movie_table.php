<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie', function (Blueprint $table) {
            $table->id();
            $table->text('body');
            $table->string('cert');
            $table->string('class');
            $table->integer('duration');
            $table->string('headline');
            $table->string('movie_id');
            $table->date('last_updated');
            $table->string('quote')->nullable();
            $table->integer('rating')->nullable();
            $table->string('review_author')->nullable();
            $table->string('sky_go_id')->nullable();
            $table->string('sky_go_url')->nullable();
            $table->string('sum');
            $table->text('synopsis');
            $table->string('url');

            // viewingWindow
            $table->string('vw_title')->nullable();
            $table->date('vw_start_date')->nullable();
            $table->string('vw_way_to_watch')->nullable();
            $table->date('vw_end_date')->nullable();

            $table->integer('year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie');
    }
}
