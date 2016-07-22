<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Sending extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'sending';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('to_messenger_id', 'from_messenger_id', 'done');

    /**
     * Sendings, that was created during this week
     *
     * @param $query
     * @return mixed
     */
    public function scopeThisWeek($query)
    {
        return $query->where('created_at', '>', Carbon::now()->startOfWeek());
    }

    /**
     * Sendings, that was created last this week
     *
     * @param $query
     * @return mixed
     */
    public function scopeLastWeek($query)
    {
        return $query->where('created_at', '>', Carbon::now()->startOfWeek()->subWeek());
    }
}
