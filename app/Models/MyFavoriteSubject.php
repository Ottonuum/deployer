<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MyFavoriteSubject extends Model
{
    protected $fillable = [
        'title',
        'image',
        'description',
        'element_type',
        'power_level'
    ];

    protected static function boot()
    {
        parent::boot();

        // Clear cache when model is updated
        static::saved(function ($model) {
            Cache::forget('favorite_subjects');
        });

        static::deleted(function ($model) {
            Cache::forget('favorite_subjects');
        });
    }

    public static function getCachedSubjects($limit = null)
    {
        return Cache::remember('favorite_subjects', 60, function () use ($limit) {
            $query = self::orderBy('created_at', 'desc');
            if ($limit) {
                $query->limit($limit);
            }
            return $query->get();
        });
    }
} 