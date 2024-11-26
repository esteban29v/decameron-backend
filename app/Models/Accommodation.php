<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation',
    ];

    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'room_type_accommodation');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
