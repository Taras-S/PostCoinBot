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
    protected $fillable = array('to', 'from', 'where', 'amount', 'type', 'done');

}
