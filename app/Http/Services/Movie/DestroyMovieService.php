<?php


namespace App\Http\Services\Movie;

use App\Http\Requests\Movie\StoreMovieRequest;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @author  Walter Discher Cechinel <mistrim@gmail.com>
 * @package App\Http\Services\Movie
 */
class DestroyMovieService
{
    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param int $id
     *
     * @return array
     * @throws \Throwable
     */
    static public function handle(int $id): array
    {
        return DB::transaction(function () use ($id) {
            /** @var StoreMovieRequest $request */

            $movie = Movie::findOrFail($id);

            $tagsDetachedAmount = $movie->tags()->detach();

            if (!$movie->delete()) {
                throw new \BadMethodCallException('Could not delete the given movie');
            }

            static::handleRemoveStoredFile($movie);

            return [
                'movie' => $movie->name,
                'tags_detached_amount' => $tagsDetachedAmount,
            ];
        });
    }

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param Movie $movie
     *
     * @return bool
     */
    static protected function handleRemoveStoredFile(Movie $movie): bool
    {
        $storage = Storage::disk('public');

        return Storage::disk('public')->delete($movie->getRelativeStoredFile());
    }
}
