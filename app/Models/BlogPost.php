<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $table = 'blog_posts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'article_title',
        'article_category',
        'article_content',
        'article_cover',
        'article_status',
        'author',
    ];
}
