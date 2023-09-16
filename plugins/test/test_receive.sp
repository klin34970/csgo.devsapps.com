#pragma semicolon 1

#define TAG "[TEST_RECEIVE] -"
#define PLUGIN_VERSION "1.0.0"
#define CVARS FCVAR_SPONLY|FCVAR_REPLICATED|FCVAR_NOTIFY
#define DEFAULT_FLAGS FCVAR_NOTIFY

#undef REQUIRE_PLUGIN
#include <dynamic>

#pragma newdecls required

public Plugin myinfo ={
    name = "test_receive",
    author = "Dr. Api",
    description = "",
    version = PLUGIN_VERSION,
    url = "https://csgo.devsapps.com"
}

public void OnPluginStart() {
    RegConsoleCmd("r", checkResponse, "");
}

public Action checkResponse(int client, int args){
    for(int i = 1 ; i <= MaxClients; i++){
        Dynamic playersettings = Dynamic.GetPlayerSettings(i).GetDynamic("infos");
        if(playersettings.IsValid){
            PrintToServer("%s client %d TestID %d", TAG, i, playersettings.GetInt("ID"));
        }else{
            PrintToServer("%s client %d !IsValid %d", TAG, i, playersettings);
        }
    }
    return Plugin_Handled;
}



