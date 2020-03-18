<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author  Walter Discher Cechinel <mistrim@gmail.com>
 *
 * @package App\Models
 */
class Movie extends Model
{

    /**
     * @var string
     */
    protected $table = 'movies';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'filename',
        'filesize',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];


    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     * @return string
     */
    public function getStoragePath(): string
    {
        return 'movies';
    }

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     * @return string
     */
    public function getStoredFileName(): string
    {
        return $this->id . '_' . $this->filename;
    }
}
