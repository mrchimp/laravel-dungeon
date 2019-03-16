<?php

namespace Dungeon;

use Dungeon\Room;
use Dungeon\Traits\HasBody;
use Dungeon\Traits\HasHealth;
use Dungeon\Traits\HasApparel;
use Dungeon\Traits\HasInventory;
use Dungeon\Entities\People\Body;
use App\Observers\SerializableObserver;
use Illuminate\Database\Eloquent\Model;
use Dungeon\Traits\HasSerializableAttributes;

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
