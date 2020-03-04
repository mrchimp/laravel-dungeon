<?php

namespace Dungeon;

use Dungeon\Entities\People\Body;
use Dungeon\Observers\SerializableObserver;
use Dungeon\Room;
use Dungeon\Traits\HasApparel;
use Dungeon\Traits\HasBody;
use Dungeon\Traits\HasHealth;
use Dungeon\Traits\HasInventory;
use Dungeon\Traits\HasSerializableAttributes;
use Illuminate\Database\Eloquent\Model;

class NPC extends Model
{
    use HasBody,
        HasHealth,
        HasSerializableAttributes,
        HasInventory,
        HasApparel;

    protected $table = 'npcs';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'serialized_data' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        self::observe(new SerializableObserver);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function body()
    {
        return $this->hasOne(Body::class, 'npc_id');
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
