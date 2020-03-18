<?php

namespace App\Http\Resources\Movie;

use App\Http\Resources\Tag\TagResource;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        /** @var $this Movie */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'filename' => $this->filename,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
            'created_at_iso' => $this->created_at->isoFormat('ddd, LL'),
            'updated_at_iso' => $this->updated_at->isoFormat('ddd, LL'),

            'tags' => TagResource::collection($this->tags),
        ];
    }
}
