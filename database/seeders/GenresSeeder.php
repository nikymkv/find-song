<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class GenresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = $this->getGenres();
        foreach ($genres as $genre) {
            Genre::create([
                'name' => $genre,
                'slug' => Str::slug($genre),
            ]);
        }
    }

    private function getGenres()
    {
        return [
            'Поп',
            'Рок',
            'Хип-хоп',
            'Реп',
            'R&B',
            'Джаз',
            'Инструментал',
            'Народная музыка',
            'Электро',
            'Транс',
        ];
    }
}
