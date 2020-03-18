<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author  Walter Discher Cechinel <mistrim@gmail.com>
 *
 * @package App\Models
 */
class Tag extends Model
{
    /**
     * @var string
     */
    protected $table = 'tags';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @author Walter Discher Cechinel <mistrim@gmail.com>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }


}
