<?php


namespace App\Http\Services\Movie;

use App\Http\Requests\Movie\StoreMovieRequest;
use App\Models\Movie;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class StoreMovieService
{
    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param StoreMovieRequest $request
     *
     * @return array
     * @throws \Throwable
     */
    static public function handle(StoreMovieRequest $request)
    {
        return DB::transaction(function () use ($request) {
            /** @var StoreMovieRequest $request */

            $model = static::create($request);

            $request->file('uploaded_file')
                ->storeAs(
                    $model->getRelativeStoragePath(), $model->getStoredFileName(), 'public'
                );

            $tags = $model->tags()->saveMany(
                static::handleTags($request->get('tags'))
            );

            return [
                'movie' => $model,
                'tags' => $tags,
            ];
        });
    }

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param StoreMovieRequest $request
     *
     * @return Movie|null
     */
    static private function create(StoreMovieRequest $request): ?Movie
    {
        $model = Movie::make($request->all());
        $filename = uniqid(time());

        $model->filesize = $request->file('uploaded_file')->getSize();
        $model->filename = $filename . '.' . $request->file('uploaded_file')->extension();

        if (!$model->save()) {
            throw new \BadMethodCallException('Failed to insert movie data.');
        }

        return $model;
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
            return Tag::firstOrCreate(['name' => $value]);
        }, $tags);
    }
}
