<?php

namespace App\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Team extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'messenger', 'messenger_id', 'access_token', 'bot_access_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
