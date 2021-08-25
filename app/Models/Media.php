<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'mediable_type', 'mediable_id', 'type', 'original_filename',
        'filename', 'path', 'extension', 'size', 'mime', 'properties'
    ];

    // Constants
    const TYPE_SSM              =   'ssm';
    const TYPE_IMAGE            =   'image';
    const TYPE_LOGO             =   'logo';
    const TYPE_PROFILE_IMAGE    =   'profile';
    const TYPE_COVER_PHOTO      =   'cover';
    const DEFAULT_IMAGE         =   'nopreview.png';
    const MAX_IMAGE_BRANCH      =   10;

    // Relationships
    public function mediable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeSsm($query)
    {
        return $query->where('type', self::TYPE_SSM);
    }

    public function scopeLogo($query)
    {
        return $query->where('type', self::TYPE_LOGO);
    }

    public function scopeImage($query)
    {
        return $query->where('type', self::TYPE_IMAGE);
    }

    public function scopeProfileImage($query)
    {
        return $query->where('type', self::TYPE_PROFILE_IMAGE);
    }

    public function scopeCoverPhoto($query)
    {
        return $query->where('type', self::TYPE_COVER_PHOTO);
    }

    // Attributes
    public function getFilePathAttribute()
    {
        return rtrim($this->path, '/') . '/' . $this->filename;
    }

    public function getFullFilePathAttribute()
    {
        return asset('storage/' . ltrim($this->file_path, '/'));
    }

    public function getSizeInBytesAttribute()
    {
        return number_format($this->size, 0, '', '');
    }

    public function getTypeInTextAttribute()
    {
        return ucwords(str_replace("-", " ", $this->type));
    }

    public function getDefaultPreviewImageAttribute()
    {
        return asset('storage/' . self::DEFAULT_IMAGE);
    }
}
