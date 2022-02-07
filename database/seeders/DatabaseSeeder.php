<?php

namespace Database\Seeders;

use App\Models\RateSong;
use App\Models\User;
use App\Models\ViewSong;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            GenresSeeder::class,
        ]);

        $users = User::where('is_moderator', false)->get();
        \App\Models\Song::factory(100)->create()->each(function ($song) use ($users) {
            foreach ($users as $user) {
                $rateSong = new RateSong([
                    'user_id' => $user->id,
                    'song_id' => $song->id,
                    'rate' => mt_rand(1, 5),
                ]);
                $rateSong->save();

                $viewSong = new ViewSong([
                    'song_id' => $song->id,
                    'user_id' => $user->id,
                    'ip' => '192.168.' . rand(0, 255) . '.' . rand(1, 254),
                ]);
                $viewSong->save();
            }
        });
    }
}
