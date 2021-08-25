<?php

namespace App\Models;

use App\Models\User;
use App\Helpers\Misc;
use App\Models\Media;
use App\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchDetail extends Model
{
    use SoftDeletes;

    protected $table = 'branch_details';

    protected $fillable = [
        'branch_id', 'reg_no', 'pic_name', 'pic_phone', 'pic_email',
        'description', 'services', 'website', 'facebook', 'whatsapp', 'instagram'
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(User::class, 'branch_id', 'id');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    // Attributes
    public function setPicContactAttribute($value)
    {
        $this->attributes['pic_contact'] = (new Misc())->phoneStoreFormat($value);
    }

    public function setWhatsappAttribute($value)
    {
        if (!empty($value)) {
            $value = (new Misc())->phoneStoreFormat($value);
        }

        $this->attributes['whatsapp'] = $value;
    }

    public function getFormattedPicPhoneAttribute()
    {
        if (empty($this->pic_phone)) {
            return null;
        }

        return (new Misc())->addTagsToPhone($this->pic_phone);
    }

    public function getFormattedWhatsappAttribute()
    {
        if (empty($this->whatsapp)) {
            return null;
        }

        return (new Misc())->addTagsToPhone($this->whatsapp);
    }
}
