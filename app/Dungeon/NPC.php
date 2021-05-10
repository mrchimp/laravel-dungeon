<?php

namespace Dungeon;

use Dungeon\Entities\People\Body;
// use Dungeon\Observers\SerializableObserver;
use Dungeon\Room;
use Dungeon\Traits\HasApparel;
use Dungeon\Traits\HasBody;
use Dungeon\Traits\HasHealth;
use Dungeon\Traits\HasInventory;
// use Dungeon\Traits\HasSerializableAttributes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NPC extends Model
{
    use HasBody,
        HasHealth,
        // HasSerializableAttributes,
        HasInventory,
        HasApparel;

    protected $table = 'npcs';

    protected $fillable = [
        'name',
        'description',
    ];

    // protected $casts = [
    //     'serialized_data' => 'array',
    // ];

    // public static function boot()
    // {
        // parent::boot();

        // self::observe(new SerializableObserver);
    // }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function body(): HasOne
    {
        return $this->hasOne(Body::class, 'npc_id');
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(Entity::class, 'npc_id');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getEquiped(): Collection
    {
        return $this
            ->body
            ->contents()
            ->whereHas('equipable', function ($query) {
                $query->where('is_equiped', true);
            })
            ->get();
    }
}
