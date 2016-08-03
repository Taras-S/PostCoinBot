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
        'messenger_id', 'name', 'wallet'
    ];

    /**
     * Sendings that member received
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivedSendings()
    {
        return $this->hasMany(Sending::class, 'recipient_id');
    }

    /**
     * Sendings that member sended
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sendedSendings()
    {
        return $this->hasMany(Sending::class, 'sender_id');
    }

    /**
     * Members with undefined names
     *
     * @param $query
     * @return mixed
     */
    public function scopeWithoutName($query)
    {
        return $query->where('name', 'NULL');
    }
}
