<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\User as UserResource;
use App\User;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use Illuminate\Http\Response;

use App\Events\UserRegister;
use App\Events\UserLogin;

class UserController extends Controller
{
    public function register(Request $request){
        try {
            $steamid = filter_var($request->header('X-Game-Player-SteamID64'), FILTER_SANITIZE_NUMBER_INT);

            $parameters = [
                'key'       => config('steam.api.key'),
                'steamids'  => $steamid,
            ];

            $query = http_build_query($parameters);
            $url = config('steam.api.GetPlayerSummaries') . $query;

            $client     = new Client([
                'headers' => [
                    'Accept'            => 'application/json',
                    'Content-Type'      => 'application/json'
                ]
            ]);

            $response   = $client->request('GET', $url);
            $json       = $response->getBody()->getContents();
            $object     = json_decode($json);

            $player     = $object->response->players[0];
            $user       = User::updateOrCreate(
                [
                    'steamid'       => $player->steamid,
                ],
                [
                    'steamid'       => $player->steamid,
                    'personaname'   => $player->personaname,
                    'profileurl'    => $player->profileurl,
                    'avatar'        => $player->avatar,
                    'avatarmedium'  => $player->avatarmedium,
                    'avatarfull'    => $player->avatarfull,
                ]
            );

            $support = 'api';
            if($request->header('X-Game-Name')){
                $support = $request->header('X-Game-Name');
            }
            $ip = '0.0.0.0';
            if($request->header('X-Game-Player-Ip')){
                $ip = $request->header('X-Game-Player-Ip');
            }
            $country = '_';
            if($request->header('X-Game-Player-Country')){
                $country = $request->header('X-Game-Player-Country');
            }
            if($user->wasRecentlyCreated){
                event(new UserRegister($user, $ip, $support, $country));
            }else{
                event(new UserLogin($user, $ip, $support, $country));
            }

            return (new UserResource($user));

        }catch (RequestException $e) {
            return response()->json([
                'data' => [
                    'message'       => $e->getMessage(),
                    'status_code'   => Response::HTTP_NOT_FOUND
                ]
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function me(Request $request){
            $steamid = filter_var($request->header('X-Game-Player-SteamID64'), FILTER_SANITIZE_NUMBER_INT);

            $user = User::where('steamid', $steamid)->firstOrFail();
            return (new UserResource($user));
    }
}
