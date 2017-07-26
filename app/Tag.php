<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function belongsToManyArticle()
    {
        return $this->belongsToMany('App\Articles', 'article_tag', 'article_id', 'id');
    }

    public function getTagsById($tid)
    {
        $tags = Tag::find($tid)->belongsToManyArticle()->get();

        return $tags;
    }
}
