<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'number_of_rooms',
        'address',
        'nit',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}