<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleText extends Model
{
    protected $table = 'article_text';
    protected $primaryKey = 'article_text_id';
    public $timestamps = false;
}
