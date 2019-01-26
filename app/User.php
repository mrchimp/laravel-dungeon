<?php

namespace App;

use App\Dungeon\Traits\HasApparel;
use App\Dungeon\Traits\HasHealth;
use App\Dungeon\Traits\HasInventory;
use App\Dungeon\Traits\HasSerializableAttributes;
use App\Observers\SerializableObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable,
        HasHealth,
        HasSerializableAttributes,
        HasInventory,
        HasApparel;

    protected $casts = [
        'data' => 'array',
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

    public function getSerializable()
    {
        return [
            'health',
        ];
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function moveTo(Room $room)
    {
        return $this->room()->associate($room);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getInventory($refresh = false)
    {
        if ($refresh) {
            $this->load('inventory');
        }

        return $this->inventory;
    }
}
