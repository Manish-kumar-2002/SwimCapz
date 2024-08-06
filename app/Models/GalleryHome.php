<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryHome extends Model
{
    protected $table = 'galleries_home';
    protected $fillable = ['id','title','image','logo','status'];
}