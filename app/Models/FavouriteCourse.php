<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteCourse extends Model
{
    use HasFactory;
    protected $table = 'favourite_courses';
    protected $fillable = [
        'user_id',
        'course_id',
    ];

    protected $hidden = ['id' , 'course_id','created_at' ,'updated_at'];

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }
}
