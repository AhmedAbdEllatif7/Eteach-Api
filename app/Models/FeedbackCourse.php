<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackCourse extends Model
{
    use HasFactory;
    protected $fillable = [
        'body',
        'course_id',
    ];

    protected $hidden = ['course_id'] ;

}
