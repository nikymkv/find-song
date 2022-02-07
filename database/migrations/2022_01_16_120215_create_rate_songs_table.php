<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_songs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('song_id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('rate');

            $table->unique(['song_id', 'user_id']);

            $table->foreign('song_id')->references('id')->on('songs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rate_songs');
    }
}
