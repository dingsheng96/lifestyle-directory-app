<?php

namespace App\Models;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes;

    protected $table = 'languages';

    protected $fillable = [
        'name', 'code', 'current_version', 'default'
    ];

    protected $casts = [
        'default' => 'boolean'
    ];

    // Constants
    const CODE_EN = 'en';

    // Relationships
    public function translations()
    {
        return $this->hasMany(Translation::class, 'language_id', 'id');
    }

    // Scopes
    public function scopeDefault($query, bool $status = true)
    {
        return $query->where('default', $status);
    }
}
