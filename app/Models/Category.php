<?php

namespace App\Models;

use App\Models\User;
use App\Models\Media;
use App\Helpers\Status;
use App\Models\Categorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    const STORE_PATH = '/categories';

    protected $table = 'categories';

    protected $fillable = [
        'name', 'description', 'status'
    ];

    // Constants
    const STATUS_PUBLISH    = 'publish';
    const STATUS_DRAFT      = 'draft';

    // Relationships
    public function merchants()
    {
        return $this->morphedByMany(User::class, 'categorizable', Categorizable::class);
    }

    public function media()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    // Scopes
    public function scopePublish($query)
    {
        return $query->where('status', self::STATUS_PUBLISH);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $label = (new Status())->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }
}
