<?php

namespace App\Models;

use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translation extends Model
{
    use SoftDeletes;

    protected $table = 'translations';

    protected $fillable = [
        'language_id', 'key', 'value'
    ];

    // Relationships
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}
