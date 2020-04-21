<?php

namespace Dungeon;

use Dungeon\Entities\People\Body;
use Dungeon\Observers\UuidObserver;
use Dungeon\Portal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'description',
    ];

    protected $exits = [];

    public static function boot()
    {
        parent::boot();

        self::observe(new UuidObserver);
    }

    public function people(Body $exclude = null): Collection
    {
        return $this
            ->contents
            ->people($exclude);
    }

    public function npcs(): Collection
    {
        return $this
            ->contents
            ->npcs();
    }

    public function items(): Collection
    {
        return $this
            ->contents
            ->items();
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Entity::class, 'room_id');
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function northExits(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'portal_room_room', 'south_room_id', 'north_room_id')
            ->withPivot('portal_id');
    }

    public function southExits(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'portal_room_room', 'north_room_id', 'south_room_id')
            ->withPivot('portal_id');
    }

    public function westExits(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'portal_room_room', 'east_room_id', 'west_room_id')
            ->withPivot('portal_id');
    }

    public function eastExits(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'portal_room_room', 'west_room_id', 'east_room_id')
            ->withPivot('portal_id');
    }

    public function northPortals(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class, 'portal_room_room', 'south_room_id', 'portal_id')
            ->withPivot('south_room_id');
    }

    public function southPortals(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class, 'portal_room_room', 'north_room_id', 'portal_id')
            ->withPivot('north_room_id');
    }

    public function eastPortals(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class, 'portal_room_room', 'west_room_id', 'portal_id')
            ->withPivot('west_room_id');
    }

    public function westPortals(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class, 'portal_room_room', 'east_room_id', 'portal_id')
            ->withPivot('east_room_id');
    }

    public function setNorthExit(Room $room, array $data = []): self
    {
        $this->northExits()->attach($room, $data);

        return $this;
    }

    public function setSouthExit(Room $room, array $data = []): self
    {
        $this->southExits()->attach($room, $data);

        return $this;
    }

    public function setWestExit(Room $room, array $data = []): self
    {
        $this->westExits()->attach($room, $data);

        return $this;
    }

    public function setEastExit(Room $room, array $data = []): self
    {
        $this->eastExits()->attach($room, $data);

        return $this;
    }

    public function getNorthExitAttribute(): ?Room
    {
        return $this->northExits->first();
    }

    public function getSouthExitAttribute(): ?Room
    {
        return $this->southExits->first();
    }

    public function getWestExitAttribute(): ?Room
    {
        return $this->westExits->first();
    }

    public function getEastExitAttribute(): ?Room
    {
        return $this->eastExits->first();
    }

    public function getNorthPortalAttribute(): ?Portal
    {
        if (!$this->northExit) {
            return null;
        }

        if ($this->northPortals->isEmpty()) {
            return Portal::makeGeneric();
        } else {
            return $this->northPortals->first();
        }
    }

    public function getSouthPortalAttribute(): ?Portal
    {
        if (!$this->southExit) {
            return null;
        }

        if ($this->southPortals->isEmpty()) {
            return Portal::makeGeneric();
        } else {
            return $this->southPortals->first();
        }
    }

    public function getEastPortalAttribute(): ?Portal
    {
        if (!$this->eastExit) {
            return null;
        }

        if ($this->eastPortals->isEmpty()) {
            return Portal::makeGeneric();
        } else {
            return $this->eastPortals->first();
        }
    }

    public function getWestPortalAttribute(): ?Portal
    {
        if (!$this->westExit) {
            return null;
        }

        if ($this->westPortals->isEmpty()) {
            return Portal::makeGeneric();
        } else {
            return $this->westPortals->first();
        }
    }

    public function toArray(): array
    {
        $output = [
            'uuid' => $this->uuid,
            'description' => $this->description,
        ];

        return $output;
    }
}
