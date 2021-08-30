<?php

namespace App\Models;

use App\Models\Media;
use App\Helpers\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;

    protected $table = 'banners';

    protected $fillable = [
        'title', 'description', 'status'
    ];

    // constants
    const STORE_PATH        = '/banners';
    const STATUS_PUBLISH    = 'publish';
    const STATUS_DRAFT      = 'draft';

    // Relationships
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
