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
     * Returns recipients ordered by total amount
     *
     * @param array $period
     * @param $limit
     * @return mixed
     */
    static function getTopRecipients(array $period, $limit)
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
