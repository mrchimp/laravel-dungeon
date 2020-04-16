<?php

namespace Dungeon;

use Dungeon\Entities\People\Body;
use Dungeon\Portal;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'description',
    ];

    protected $exits = [];

    public function people(Body $exclude = null)
    {
        return $this
            ->contents
            ->people($exclude);
    }

    public function npcs()
    {
        return $this
            ->contents
            ->npcs();
    }

    public function items()
    {
        return $this
            ->contents
            ->items();
    }

    public function contents()
    {
        return $this->hasMany(Entity::class, 'room_id');
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function northExits()
    {
        return $this->belongsToMany(Room::class, 'portal_room_room', 'south_room_id', 'north_room_id')
            ->withPivot('description', 'portal_id')
            ->as('portal');
    }

    public function southExits()
    {
        return $this->belongsToMany(Room::class, 'portal_room_room', 'north_room_id', 'south_room_id')
            ->withPivot('description', 'portal_id')
            ->as('portal');
    }

    public function westExits()
    {
        return $this->belongsToMany(Room::class, 'portal_room_room', 'east_room_id', 'west_room_id')
            ->withPivot('description', 'portal_id')
            ->as('portal');
    }

    public function eastExits()
    {
        return $this->belongsToMany(Room::class, 'portal_room_room', 'west_room_id', 'east_room_id')
            ->withPivot('description', 'portal_id')
            ->as('portal');
    }

    public function northPortals()
    {
        return $this->belongsToMany(Portal::class, 'portal_room_room', 'south_room_id', 'portal_id')
            ->withPivot('description', 'south_room_id')
            ->as('portal');
    }

    public function southPortals()
    {
        return $this->belongsToMany(Portal::class, 'portal_room_room', 'north_room_id', 'portal_id')
            ->withPivot('description', 'north_room_id')
            ->as('portal');
    }

    public function eastPortals()
    {
        return $this->belongsToMany(Portal::class, 'portal_room_room', 'west_room_id', 'portal_id')
            ->withPivot('description', 'west_room_id')
            ->as('portal');
    }

    public function westPortals()
    {
        return $this->belongsToMany(Portal::class, 'portal_room_room', 'east_room_id', 'portal_id')
            ->withPivot('description', 'east_room_id')
            ->as('portal');
    }

    public function setNorthExit($room, $data = [])
    {
        $this->northExits()->attach($room, $data);

        return $this;
    }

    public function setSouthExit($room, $data = [])
    {
        $this->southExits()->attach($room, $data);

        return $this;
    }

    public function setWestExit($room, $data = [])
    {
        $this->westExits()->attach($room, $data);

        return $this;
    }

    public function setEastExit($room, $data = [])
    {
        $this->eastExits()->attach($room, $data);

        return $this;
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

    public function getNorthPortalAttribute()
    {
        return $this->northPortals->first();
    }

    public function getSouthPortalAttribute()
    {
        return $this->southPortals->first();
    }

    public function getEastPortalAttribute()
    {
        return $this->eastPortals->first();
    }

    public function getWestPortalAttribute()
    {
        return $this->westPortals->first();
    }

    public function toArray()
    {
        $output = [
            'id' => $this->id,
            'description' => $this->description,
        ];

        return $output;
    }
}
