<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'instructor_id',
        'instructor_name'
    ];

    public static function table(string $string)
    {

    }
    public function feedback()
    {
        return $this->hasMany(FeedbackCourse::class,'course_id');
    }
    public function video_course()
    {
        return $this->hasMany(VideosCourse::class,'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'instructor_id');
    }

    public function fav_course()
    {
        return $this->hasOne(FavouriteCourse::class , 'course_id');
    }
}
