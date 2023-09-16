<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\UserConnection;

use App\User;

class ConnectionController extends Controller
{
    public function me(Request $request){

        $steamid = filter_var($request->header('X-Game-Player-SteamID64'), FILTER_SANITIZE_NUMBER_INT);
        $user = User::where('steamid', $steamid)->firstOrFail();

        return UserConnection::collection($user->connections()->orderBy('id', 'DESC')->paginate(30));
    }
}
