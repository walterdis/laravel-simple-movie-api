<?php

use App\Models\Movie;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class MovieSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Movie::class, 5)->create()->each(function (Movie $movie) {
            $tags = Tag::inRandomOrder()->limit(rand(1, 10))->get();
            $movie->tags()->saveMany($tags);
        });
    }
}
