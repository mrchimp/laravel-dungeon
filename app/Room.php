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

    public function contents()
    {
        return $this->hasMany(Entity::class, 'room_id');
    }

    public function exits()
    {
        return $this->belongsToMany();
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getExits()
    {
        return $this->exits;
    }

    public function northExits()
    {
        return $this->belongsToMany(Room::class, 'portals', 'south_room_id', 'north_room_id')
            ->using(Portal::class)
            ->withPivot('description')
            ->as('portal');
    }

    public function southExits()
    {
        return $this->belongsToMany(Room::class, 'portals', 'north_room_id', 'south_room_id')
            ->using(Portal::class)
            ->withPivot('description')
            ->as('portal');
    }

    public function westExits()
    {
        return $this->belongsToMany(Room::class, 'portals', 'east_room_id', 'west_room_id')
            ->using(Portal::class)
            ->withPivot('description')
            ->as('portal');
    }

    public function eastExits()
    {
        return $this->belongsToMany(Room::class, 'portals', 'east_room_id', 'west_room_id')
            ->using(Portal::class)
            ->withPivot('description')
            ->as('portal');
    }

    public function setNorthExit($room, $data = [])
    {
        $this->northExits()->attach($room, $data);
    }

    public function setSouthExit($room, $data = [])
    {
        $this->southExits()->attach($room, $data);
    }

    public function setWestExit($room, $data = [])
    {
        $this->westExits()->attach($room, $data);
    }

    public function setEastExit($room, $data = [])
    {
        $this->eastExits()->attach($room, $data);
    }

    public function getNorthExitAttribute()
    {
        return $this->northExits->first();
    }

    public function getSouthExitAttribute()
    {
        return $this->southExits->first();
    }

    public function getWestExitAttribute()
    {
        return $this->westExits->first();
    }

    public function getEastExitAttribute()
    {
        return $this->eastExits->first();
    }
}
