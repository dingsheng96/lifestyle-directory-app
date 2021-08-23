<?php

namespace App\Models;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes;

    const CODE_EN = 'en';

    protected $table = 'languages';

    protected $fillable = [
        'name', 'code', 'current_version', 'default'
    ];

    protected $casts = [
        'default' => 'boolean'
    ];

    // Relationships
    public function translations()
    {
        return $this->hasMany(Translation::class, 'language_id', 'id');
    }
}
