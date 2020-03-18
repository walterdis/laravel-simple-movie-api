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
    public function getRelativeStoragePath(): string
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

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     * @return string
     */
    public function getRelativeStoredFile(): string
    {
        if (!$this->getStoredFileName()) {
            throw new \BadMethodCallException('The given filename is invalid.');
        }

        return $this->getRelativeStoragePath() . DIRECTORY_SEPARATOR . $this->getStoredFileName();
    }
}
