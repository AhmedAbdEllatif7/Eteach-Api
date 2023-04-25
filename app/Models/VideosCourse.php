<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideosCourse extends Model
{
    use HasFactory;
    protected $fillable = [
        'videos',
        'course_id',
        'name',
    ];
    protected $hidden = ['course_id'] ;
}
