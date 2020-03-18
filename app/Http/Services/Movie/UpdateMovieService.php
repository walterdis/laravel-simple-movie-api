<?php


namespace App\Http\Services\Movie;

use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use App\Models\Movie;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

/**
 * @author  Walter Discher Cechinel <mistrim@gmail.com>
 * @package App\Http\Services\Movie
 */
class UpdateMovieService
{
    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param UpdateMovieRequest $request
     *
     * @param int $id
     *
     * @return void
     * @throws \Throwable
     */
    static public function handle(UpdateMovieRequest $request, int $id): array
    {
        return DB::transaction(function () use ($request, $id) {
            /** @var StoreMovieRequest $request */

            $movie = Movie::findOrFail($id)->fill([
                'name' => $request->get('name'),
            ]);

            $movie->saveOrFail();

            $tags = $movie->tags()->sync(
                static::handleTags($request->get('tags'))
            );

            return [
                'movie' => $movie,
                'tags' => $tags,
            ];
        });
    }

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param array|null $tags
     *
     * @return array
     */
    static protected function handleTags(?array $tags)
    {
        if (!$tags) {
            return [];
        }
        return array_map(function ($value) {
            return Tag::firstOrCreate(['name' => $value])->id;
        }, $tags);
    }
}
