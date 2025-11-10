<?php

namespace App\Models\Articles;

use App\Models\Images\Image;
use App\Models\User;
use Dom\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
     protected $fillable = ['title', 'content', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function comments()
{
    return $this->hasMany(Comment::class);
}
}
