<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSocialMedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_social_media';

    protected $fillable = [
        'media_value'
    ];

    // Constants
    const SOCIAL_MEDIA_KEY_FACEBOOK    = 'facebook';
    const SOCIAL_MEDIA_KEY_WHATSAPP    = 'whatsapp';
    const SOCIAL_MEDIA_KEY_WEBSITE     = 'website';
    const SOCIAL_MEDIA_KEY_INSTAGRAM   = 'instagram';
    const SOCIAL_MEDIA_KEY_TWITTER     = 'twitter';
    const SOCIAL_MEDIA_KEY_LINKEDIN    = 'linkedin';
    const SOCIAL_MEDIA_KEY_SHOPEE      = 'shopee';
    const SOCIAL_MEDIA_KEY_LAZADA      = 'lazada';
    const SOCIAL_MEDIA_KEY_YOUTUBE     = 'youtube';
    const SOCIAL_MEDIA_KEY_ECATALOGUE  = 'e_catalogue';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
