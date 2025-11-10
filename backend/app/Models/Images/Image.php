<?php

namespace App\Models\Images;

use App\Models\Articles\Article;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = ['path', 'article_id'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
