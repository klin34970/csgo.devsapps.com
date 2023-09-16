<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBan extends Model
{
    protected $table = 'users_bans';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'user_id',
        'judge_id',
        'reason',
        'date_start',
        'date_end',
    ];

    protected $cast = [
        'user_id'       => 'integer',
        'judge_id'      => 'integer',
        'reason'        => 'string',
        'date_start'    => 'datetime',
        'date_start'    => 'datetime',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function judge(){
        return $this->belongsTo('App\User');
    }
}
