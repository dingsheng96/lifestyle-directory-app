<?php

namespace App\Models;

use App\Models\User;
use App\Models\Media;
use App\Models\Categorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name', 'description'
    ];

    // Relationships
    public function merchants()
    {
        return $this->morphedByMany(User::class, 'categorizable', Categorizable::class);
    }

    public function image()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
