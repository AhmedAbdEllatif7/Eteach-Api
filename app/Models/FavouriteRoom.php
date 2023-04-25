<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteRoom extends Model
{
    use HasFactory;

    protected $table = 'favourite_rooms';
    protected $fillable = [
        'user_id',
        'room_id',
    ];
    protected $hidden = ['id', 'room_id','created_at' ,'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class,'room_id');
    }
}
