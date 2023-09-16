<?php

namespace App;

use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Carbon\Carbon;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens, Notifiable;

    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'steamid',
        'personaname',
        'profileurl',
        'avatar',
        'avatarmedium',
        'avatarfull',
    ];

    protected $cast = [
        'id'            => 'integer',
        'steamid'       => 'integer',
        'personaname'   => 'string',
        'profileurl'    => 'string',
        'avatar'        => 'string',
        'avatarmedium'  => 'string',
        'avatarfull'    => 'string',
    ];

    public function connections(){
        return $this->hasMany('App\UserConnection');
    }

    public function bans(){
        return $this->hasMany('App\UserBan');
    }

    public function isBan(){
        if($this->bans->count()) {
            $ban = $this->bans->last();
            $date_start = Carbon::parse($ban->date_start);
            $date_start = $date_start->lessThanOrEqualTo(now());

            $date_end = Carbon::parse($ban->date_end);
            $date_end = $date_end->greaterThanOrEqualTo(now());

            if ($date_start && $date_end) {
                return true;
            }
        }
        return false;
    }
}
