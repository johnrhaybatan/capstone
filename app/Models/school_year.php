<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class school_year extends Model
{
    use HasFactory;
    protected $table = 'school_year';
    protected $fillable = [
        'S_Y',
        'school_yearid',

    ];
}
