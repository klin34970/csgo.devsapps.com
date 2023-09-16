<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserConnection extends Model
{
    protected $table = 'users_connections';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'user_id',
        'ip',
        'support',
        'country',
    ];

    protected $cast = [
        'user_id'   => 'integer',
        'ip'        => 'string',
        'support'   => 'string',
        'country'   => 'string',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
