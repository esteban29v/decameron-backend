<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
    ];

    public function accommodations()
    {
        return $this->belongsToMany(Accommodation::class, 'room_type_accommodation');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
