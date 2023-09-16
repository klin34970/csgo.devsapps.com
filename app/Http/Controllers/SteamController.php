<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use App\User;

use App\Events\UserRegister;
use App\Events\UserLogin;


class SteamController extends Controller
{
    public function login(Request $request)
    {
        $openid = new \LightOpenID($request->getHost());
        switch($openid->mode){
            case null:
                    $openid->identity = config('steam.openid');
                    return redirect($openid->authUrl());
                break;
            case 'cancel':
                    return redirect('/');
                break;
            default:
                if($openid->validate()){
                    try {
                        $id = $openid->identity;
                        $ptn = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
                        preg_match($ptn, $id, $matches);

                        $parameters = [
                            'key'       => config('steam.api.key'),
                            'steamids'  => $matches[1],
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

                        auth()->login($user, true);

                        $country = '_';
                        if(isset($player->loccountrycode)){
                            $country = $player->loccountrycode;
                        }
                        if($user->wasRecentlyCreated){
                            event(new UserRegister($user, $request->getClientIp(), 'web', $country));
                        }else{
                            event(new UserLogin($user, $request->getClientIp(), 'web', $country));
                        }
                        return redirect('/');

                    }catch (RequestException $e) {
                        return redirect('/');
                    }
                }
                break;
        }
        return redirect('/');
    }
}
