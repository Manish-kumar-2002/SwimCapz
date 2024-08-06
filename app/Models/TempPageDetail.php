<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempPageDetail extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id', 'result'
    ];
    
    public function getResultAttribute($value)
    {
        return json_decode($value, true);
    }
}
