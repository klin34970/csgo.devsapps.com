<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserConnection;
use App\Http\Resources\UserBan;

use Lang, Config;

class User extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $language = Lang::locale();
        if(in_array($request->header('X-Game-Player-Language'), Config::get('app.locales'))) {
            $language = filter_var($request->header('X-Game-Player-Language'), FILTER_SANITIZE_STRING);
        }
        return [
            'id'                => (string)$this->id,
            'steamid'           => (string)$this->steamid,
            'personaname'       => (string)$this->personaname,
            'profileurl'        => (string)$this->profileurl,
            'avatar'            => (string)$this->avatar,
            'avatarmedium'      => (string)$this->avatarmedium,
            'avatarfull'        => (string)$this->avatarfull,
            'created_at'        => (string)$this->created_at,
            'updated_at'        => (string)$this->updated_at,
            'last_connection'   => $this->connections->count() ? (new UserConnection($this->connections->last())) : null,
            'count_connection'  => $this->connections->count(),
            'last_ban'          => $this->bans->count() ? (new UserBan($this->bans->last())) : null,
            'count_ban'         => $this->bans->count(),
            'ban'               => $this->isBan(),
            'permissions'       => $this->getAllPermissions(),
        ];
    }

}
