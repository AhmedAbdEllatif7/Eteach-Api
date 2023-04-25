<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name' , 'description', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User' , 'user_id');
    }

    public function fav_room()
    {
        return $this->hasOne(FavouriteRoom::class , 'room_id');
    }



}
