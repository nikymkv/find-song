<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdmin();
        $this->createUsers();
    }

    private function createAdmin()
    {
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'is_moderator' => true,
            'preview_image' => 'users/default.png',
        ]);
    }

    private function createUsers()
    {
        $data = [
            [
                'name' => 'Nikolay Makeev',
                'username' => 'nikymkv',
                'email' => 'makeev@mail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'preview_image' => 'users/default.png',
            ],
            [
                'name' => 'Vlad Vovchenko',
                'username' => 'wizziglod',
                'email' => 'wizziglod@mail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'preview_image' => 'users/default.png',
            ],
        ];

        foreach ($data as $item) {
            User::create($item);
        }
    }
}
