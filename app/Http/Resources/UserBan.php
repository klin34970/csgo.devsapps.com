<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Lang, Config;

class UserBan extends JsonResource
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

        return [
            'judge'         => (string)$this->judge->personaname,
            'reason'        => (string)trans($this->reason, [], $language),
            'date_start'    => $this->date_start,
            'date_end'      => $this->date_end,
            'sentence'      => trans('reasons.sentence', [
                'judge'         => (string)$this->judge->personaname,
                'reason'        => (string)trans($this->reason, [], $language),
                'date_start'    => $this->date_start,
                'date_end'      => $this->date_end,
            ], $language),
        ];
    }
}
