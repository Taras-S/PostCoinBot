<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sending extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('recipient_id', 'sender_id', 'done');

    /**
     * Get the sender model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sender()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the recipient model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function recipient()
    {
        return $this->belongsTo(Member::class);
    }

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
