<?php

namespace App;

use App\Dungeon\Traits\HasApparel;
use App\Dungeon\Traits\HasHealth;
use App\Dungeon\Traits\HasInventory;
use App\Dungeon\Traits\HasSerializableAttributes;
use App\Observers\SerializableObserver;
use Illuminate\Database\Eloquent\Model;

class NPC extends Model
{
    use HasHealth,
        HasSerializableAttributes,
        HasInventory,
        HasApparel;

    protected $table = 'npcs';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        self::observe(new SerializableObserver);
    }

    public function getSerializable()
    {
        return [];
    }

    public function room()
    {
        return $this->belongsTo(room::class);
    }

    public function moveTo(Room $room)
    {
        return $this->room()->associate($room);
    }

    public function inventory()
    {
        return $this->hasMany(Entity::class, 'npc_id');
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
