<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'description',
    ];

    protected $exits = [];

    public function people()
    {
        return $this->hasMany(User::class, 'room_id');
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getExits()
    {
        return $this->exits;
    }
}
