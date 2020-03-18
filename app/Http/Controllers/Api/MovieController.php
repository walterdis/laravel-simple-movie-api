<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use App\Http\Resources\Movie\MovieResource;
use App\Http\Services\Movie\DestroyMovieService;
use App\Http\Services\Movie\StoreMovieService;
use App\Http\Services\Movie\UpdateMovieService;
use App\Models\Movie;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Throwable;

class MovieController extends Controller
{
    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param string $order
     *
     * @return AnonymousResourceCollection
     */
    public function index($order = null)
    {
        $movies = Movie::with('tags');

        if ($order) {
            $movies->orderBy('name', $order);
        }

        $movies->latest();

        return MovieResource::collection($movies->get());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Movie|Movie[]|Collection|Model
     */
    public function show($id)
    {
        return Movie::findOrFail($id);
    }

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param StoreMovieRequest $request
     *
     * @return array|ResponseFactory|Response
     * @throws Throwable
     */
    public function store(StoreMovieRequest $request)
    {
        try {
            return StoreMovieService::handle($request);
        } catch (\BadMethodCallException | \Exception $e) {
            return \response([
                'data' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param UpdateMovieRequest $request
     * @param int $id
     *
     * @return array|ResponseFactory|Response|void
     * @throws Throwable
     */
    public function update(UpdateMovieRequest $request, $id)
    {
        try {
            return UpdateMovieService::handle($request, $id);
        } catch (ModelNotFoundException | \Exception $e) {
            return \response([
                'data' => $e->getMessage(),
            ], 400);
        }

    }

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param int $id
     *
     * @return array|ResponseFactory|Response
     * @throws Throwable
     */
    public function destroy($id)
    {
        try {
            return DestroyMovieService::handle($id);
        } catch (ModelNotFoundException | \Exception $e) {
            return \response([
                'data' => $e->getMessage(),
            ], 400);
        }
    }
}
