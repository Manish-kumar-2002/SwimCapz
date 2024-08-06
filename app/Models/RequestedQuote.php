<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestedQuote extends Model
{
    use HasFactory;

    protected $fillable=[
        'name' ,'email','phone','project_desc','noi','date','budget','front','back','side3','side4','file'
    ];
}
