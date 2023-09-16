<?php

return [

    'openid' => env('STEAM_OPENID', 'http://steamcommunity.com/openid'),
    'api' => [
        'key'                   => env('STEAM_API_KEY', '45BFF494772BF21C8B6AD570DBF060C8'),
        'GetPlayerSummaries'    => env('STEAM_API_GETPLAYERSUMMARIES', 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?'),
    ]
];