<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedOn extends Model
{
    protected $table = 'featured_on';
    protected $fillable = ['id','title','description','link','image','article_date','status'];
}