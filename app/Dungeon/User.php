<?php

namespace Dungeon;

use Dungeon\Entities\People\Body;
use Dungeon\Observers\SerializableObserver;
use Dungeon\Room;
use Dungeon\Traits\Findable;
use Dungeon\Traits\HasApparel;
use Dungeon\Traits\HasBody;
use Dungeon\Traits\HasSerializableAttributes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasBody,
        Notifiable,
        HasSerializableAttributes,
        HasApparel,
        Findable;

    const DEFAULT_HEALTH = 100;

    protected $casts = [
        'serialized_data' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'health',
        'room_id',
        'can_be_attacked',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
        parent::boot();

        self::observe(new SerializableObserver);
    }

    public function getSerializable(): array
    {
        return [
            'health' => 100,
            'can_be_taken' => false,
        ];
    }

    public function body(): HasOne
    {
        return $this->hasOne(Body::class, 'owner_id');
    }

    public function getRoom(): ?Room
    {
        if (!$this->hasBody()) {
            return null;
        }

        return $this->body->getRoom();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function respawn(): self
    {
        $room = Room::spawnRoom()->inRandomOrder()->first();

        $body = Body::create([
            'name' => 'Temp name',
            'description' => 'Body',
        ]);

        $body->moveToRoom($room)
            ->giveToUser($this)
            // @todo default health should be per-class/race
            ->setHealth(self::DEFAULT_HEALTH)
            ->save();

        return $this;
    }

    public function isDead()
    {
        if (!$this->hasBody()) {
            return true;
        }

        if ($this->body->isDead()) {
            return true;
        }

        return !$this->body->room;
    }

    public function isAlive()
    {
        return !$this->isDead();
    }

    public function canBeAttacked(): bool
    {
        return $this->can_be_attacked;
    }
}
