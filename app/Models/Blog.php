<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id'
    ];

    /**
     * Get the comments for the blog post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get only approved comments
     */
    public function approvedComments()
    {
        return $this->comments()->where('is_approved', true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
