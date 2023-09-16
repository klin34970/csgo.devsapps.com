#if defined _drapi_included
#endinput
#endif
#define _drapi_included

#include <dynamic>
#include <ripext>
#include <geoip>


HTTPClient httpClientOrigin;

stock HTTPClient httpClient(client=0){
    httpClientOrigin = new HTTPClient("http://csgo-preprod.devsapps.com/api");
    httpClientOrigin.SetHeader("Authorization", "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjgxYjQyOTY0OTA3YmZkZmU0YmE2YzVmNzg2NWRmNDM2OTA0YmYyZGZhZmQ5OTUwMWQ5NWMwMzliNDQ1OWQzNGVmZjFhZjU3MzZlN2JjOGU1In0.eyJhdWQiOiIxIiwianRpIjoiODFiNDI5NjQ5MDdiZmRmZTRiYTZjNWY3ODY1ZGY0MzY5MDRiZjJkZmFmZDk5NTAxZDk1YzAzOWI0NDU5ZDM0ZWZmMWFmNTczNmU3YmM4ZTUiLCJpYXQiOjE1MjY1NTkzODgsIm5iZiI6MTUyNjU1OTM4OCwiZXhwIjoxNTU4MDk1Mzg4LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.QRWd-OdeQkmA8lX58v6AfA0kGsCnQGcKClSL52Cx44ZA9bOAjltGRLrjXQpeGNUm7KNAPS4jpLa1ipptb8QrHtzHX1OAYdt5HGGGuoX1ZjRb8cw4l3anXKfLGAbw0vEK77r_r7CycpH7u57bxau7K8D0SCfzPwH-hnbohyAKnU9NTt-mp7h1Hy38FXM7nc9p6D28Lm4AvvyoU82ED-k_acUCoFCmAgTzfgBzQ9ibYeSd8_VQIFM6Faguz2KnLLTaOr51KITFyVm_FIG2e_pTm2eMlhhws-2xy_F5V1bXcVWcx2vFtvg53d8xHnfigtFigv0bjkzgJIDDPYQiMqmWUoXhPeRoYRjIadIjbQtWdt4Ys1jnQWvWj5lqzOc7oGkiiakWXsm5IjyfPplY4d6JvztguYXLArq8uk5QIM4FHKYyRHLAA_RYBGZaKfO_LeghXDCO85qBdwzONQ4cHOE5ghcsPNP1yaHkOUq3ywz8jWF9_2roZZ6e-2M9ORCSwkgXOuGIwHqL2Sf5CDTzMUpqOkuNnWi1r-GveFH0j1vxVDdaMw9cJblYxDXJWDxzQ0SfDH5ieivN9weHRc5WGYYXEwn6cevjvHNENGWyYhBedGz6XoZxiluUfq-t8S_h9DGBY5pva4GJqVrBWhwzylF9-CfygkImQ8eBQRAVgB9vNXk");
    httpClientOrigin.SetHeader("Content-Type", "application/json");
    httpClientOrigin.SetHeader("Accept", "application/json");

    //X-Game-Name
    char sGame[128];
    GetGameFolderName(sGame, sizeof(sGame));
    httpClientOrigin.SetHeader("X-Game-Name", sGame);

    if(client > 0 && IsClientInGame(client) && !IsFakeClient(client)){
        //X-Game-Player-SteamID64
        char sSteamID64[32];
        GetClientAuthId(client, AuthId_SteamID64, sSteamID64, sizeof(sSteamID64));
        httpClientOrigin.SetHeader("X-Game-Player-SteamID64", sSteamID64);

        //X-Game-Player-Ip
        char sIP[45];
        GetClientIP(client, sIP, sizeof(sIP));
        httpClientOrigin.SetHeader("X-Game-Player-Ip", sIP);

        //X-Game-Player-Language
        char sCodeLang[3], sNameLang[3];
        int idLang = GetClientLanguage(client);
        GetLanguageInfo(idLang, sCodeLang, sizeof(sCodeLang), sNameLang, sizeof(sNameLang));
        httpClientOrigin.SetHeader("X-Game-Player-Language", sCodeLang);

        //X-Game-Player-Language
        char sCountry[3];
        GeoipCode2(sIP, sCountry);
        httpClientOrigin.SetHeader("X-Game-Player-Country", sCountry);

        Dynamic playersettings = Dynamic.GetPlayerSettings(client);
        if(playersettings.IsValid && playersettings.GetDynamic("infos").IsValid){
            playersettings.GetDynamic("infos").SetString("steamID64", sSteamID64);
            playersettings.GetDynamic("infos").SetString("ip", sIP);
            playersettings.GetDynamic("infos").SetString("language", sCodeLang);
            playersettings.GetDynamic("infos").SetString("country", sCountry);
        }
    }
    return httpClientOrigin;
}