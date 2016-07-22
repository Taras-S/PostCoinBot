<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'messenger_id', 'username', 'wallet'
    ];

    public function received()
    {
        return $this->hasMany(Sending::class, 'recipient_id');
    }

    public function sended()
    {
        return $this->hasMany(Sending::class, 'sender_id');
    }
}
