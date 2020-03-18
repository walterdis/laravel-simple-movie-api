<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class TagController extends Controller
{
    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @return Tag[]|\Illuminate\Database\Eloquent\Collection|Collection
     */
    public function index()
    {
        return Tag::get();
    }

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param StoreTagRequest $request
     *
     * @return Tag|Model
     */
    public function store(StoreTagRequest $request)
    {
        return Tag::create($request->all());
    }

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param StoreTagRequest $request
     * @param int $id
     *
     * @return bool
     */
    public function update(StoreTagRequest $request, $id)
    {
        return Tag::find($id)->fill($request->all())->save();
    }

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
