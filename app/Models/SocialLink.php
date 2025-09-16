<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialLink extends Model
{
    protected $table = 'social_links';

    // แพลตฟอร์มที่รองรับ (ไว้ใช้ validate + สร้าง <select>)
    public const PLATFORMS = [
        'facebook', 'twitter', 'linkedin', 'instagram',
        'github', 'youtube', 'tiktok', 'website', 'other',
    ];

    protected $fillable = [
        'user_id', 'platform', 'url', 'handle',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
