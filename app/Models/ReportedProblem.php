<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportedProblem extends Model
{
    use HasFactory;
    protected $fillable=[
        'feedback' ,'email','phone'
    ];
}
