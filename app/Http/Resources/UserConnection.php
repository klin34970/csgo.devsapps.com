<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;

use Lang, Config;

class UserConnection extends JsonResource
{
    /**
     * Transform the resource into an array.
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
        Carbon::setLocale($language);
        return [
            'ip'                    => (string)$this->ip,
            'support'               => (string)$this->support,
            'country'               => (string)$this->country,
            'created_at'            => (string)$this->created_at,
            'created_at_humans'     => (string)Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}
