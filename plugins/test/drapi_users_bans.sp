#pragma semicolon 1

#define TAG "[BANS] -"
#define PLUGIN_VERSION "1.0.0"
#define CVARS FCVAR_SPONLY|FCVAR_REPLICATED|FCVAR_NOTIFY
#define DEFAULT_FLAGS FCVAR_NOTIFY

#undef REQUIRE_PLUGIN
#include <drapi/drapi>

#pragma newdecls required

bool drapi_users = false;

public Plugin myinfo ={
    name = "drapi_users_bans",
    author = "Dr. Api",
    description = "",
    version = PLUGIN_VERSION,
    url = "https://csgo.devsapps.com"
}

public void OnAllPluginsLoaded(){
	drapi_users = LibraryExists("drapi_users");
}

public void OnLibraryRemoved(const char[] name){
	if (StrEqual(name, "drapi_users")){
		drapi_users = false;
	}
}

public void OnLibraryAdded(const char[] name){
	if (StrEqual(name, "drapi_users")){
		drapi_users = true;
	}
}

public void OnMapStart(){
    if(drapi_users){
        CreateTimer(30.0, TimerCheckBans, _, TIMER_REPEAT | TIMER_FLAG_NO_MAPCHANGE);
    }
}

public Action TimerCheckBans(Handle timer){
    if(drapi_users){
        for(int i = 1 ; i <= MaxClients; i++){
            if(IsClientInGame(i) && !IsFakeClient(i)){
                httpClient(i).Get("users/me", OnReceived, i);
            }
        }
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

    JSONArray permissions = view_as<JSONArray>(data.Get("permissions"));
    int numPermission = permissions.Length;

    JSONObject permission;
    char name[64];
    for (int i = 0; i < numPermission; i++) {
        permission = view_as<JSONObject>(permissions.Get(i));
        permission.GetString("name", name, sizeof(name));

        Dynamic playersettings = Dynamic.GetPlayerSettings(client).GetDynamic("infos");
        if(playersettings.IsValid){
            playersettings.SetBool(name, true);
        }
        delete permission;
    }

    delete permissions;
    delete data;
    delete users;
}


