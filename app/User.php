<?php

namespace App;

use App\Dungeon\Traits\HasHealth;
use App\Dungeon\Traits\HasSerializableAttributes;
use App\Observers\SerializableObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasHealth, HasSerializableAttributes;

    protected $serializable = [
        'health',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        self::observe(new SerializableObserver);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function moveTo(Room $room)
    {
        return $this->room()->associate($room);
    }
}
