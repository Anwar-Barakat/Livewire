<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
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
        $images = glob(public_path('images/*.*'));
        foreach ($images as $image) {
            unlink($image);
        }

        User::factory(10)->create();
        Post::factory(20)->create();
    }
}