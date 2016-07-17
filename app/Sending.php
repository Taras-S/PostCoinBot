<?php

namespace App;

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
    protected $fillable = array('to_name', 'to_slack_id', 'from_slack_id', 'from_name', 'where', 'amount', 'type', 'done');

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

    /**
     * Returns recipients ordered by total amount
     *
     * @param array $period
     * @param $limit
     * @return mixed
     */
    public function getTopRecipients(array $period, $limit)
    {
        return self::select(['to_name', DB::raw('SUM(amount)')])
            ->whereBetween('created_at', $period)
            ->groupBy('to_name')
            ->orderBy(DB::raw('SUM(amount)'), 'DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Returns ids of nameless (null) senders
     *
     * @return mixed
     */
    public function getUnnamedSenders()
    {
        return self::select('from_slack_id')
            ->whereNull('from_name')
            ->get();
    }

    /**
     * Returns ids of nameless (null) recipients
     *
     * @return mixed
     */
    public function getUnnamedRecipients()
    {
        return self::select('to_slack_id')
            ->whereNull('from_name')
            ->get();
    }

}
