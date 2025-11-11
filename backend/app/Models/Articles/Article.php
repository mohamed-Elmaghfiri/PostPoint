<?php

namespace App\Models\Articles;

use App\Models\Category;
use App\Models\Comments\Comment as CommentsComment;
use App\Models\Images\Image;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
     protected $fillable = ['title', 'content', 'status', 'user_id'];
   protected $appends = ['images_urls'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
     public function getImagesUrlsAttribute()
    {
        return $this->images->map(function ($image) {
            return $image->path ? asset('storage/' . $image->path) : null;
        });
    }
    public function comments()
{
    return $this->hasMany(CommentsComment::class);
}
public function category()
{
    return $this->belongsTo(Category::class);   
}
}