<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use function GuzzleHttp\default_ca_bundle;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('genre_id');
            $table->string('name', 255);
            $table->string('preview_image', 255);
            $table->string('featuring_with', 255)->nullable();
            $table->string('producer', 255);
            $table->string('text_written_by', 255);
            $table->string('music_written_by', 255);
            $table->string('mixed_by', 255);
            $table->string('text', 32768);
            $table->boolean('is_moderated')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE songs ADD COLUMN tsvector_text TSVECTOR");
        DB::statement("UPDATE songs SET tsvector_text = to_tsvector('simple'::regconfig, 'text')");
        DB::statement("CREATE INDEX vacancy_tsvector_text_gin ON songs USING GIN(tsvector_text)");
        DB::statement("CREATE TRIGGER ts_vacancy_tsvector_text BEFORE INSERT OR UPDATE ON songs FOR EACH ROW EXECUTE PROCEDURE tsvector_update_trigger(tsvector_text, 'pg_catalog.simple', 'text')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TRIGGER IF EXISTS ts_vacancy_tsvector_text ON songs");
        DB::statement("DROP INDEX IF EXISTS vacancy_tsvector_text_gin");
        DB::statement("ALTER TABLE songs DROP COLUMN tsvector_text");
        Schema::dropIfExists('songs');
    }
}
