#pragma semicolon 1

#define TAG "[TEST_SEND] -"
#define PLUGIN_VERSION "1.0.0"
#define CVARS FCVAR_SPONLY|FCVAR_REPLICATED|FCVAR_NOTIFY
#define DEFAULT_FLAGS FCVAR_NOTIFY

#undef REQUIRE_PLUGIN
#include <dynamic>
#include <drapi/test>

#pragma newdecls required


public Plugin myinfo ={
    name = "test_send",
    author = "Dr. Api",
    description = "",
    version = PLUGIN_VERSION,
    url = "https://csgo.devsapps.com"
}

public void OnPluginStart() {
    RegConsoleCmd("test", checkResponse, "");
    for(int i = 1 ; i <= MaxClients; i++){
        Dynamic playersettings = Dynamic.GetPlayerSettings(i);
        playersettings.Dispose();
        Player player = Player();
        player.SetInt("ID", 0);
        playersettings.SetDynamic("infos", player);
    }
}

public void OnPluginEnd() {
    for(int i = 1 ; i <= MaxClients; i++){
        Dynamic playersettings = Dynamic.GetPlayerSettings(i);
        playersettings.Dispose();
    }
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


public void OnClientPostAdminCheck(int client){
    Dynamic playersettings = Dynamic.GetPlayerSettings(client).GetDynamic("infos");
    playersettings.SetInt("ID", client);
}

public void OnClientDisconnect(int client){
    Dynamic playersettings = Dynamic.GetPlayerSettings(client).GetDynamic("infos");
    playersettings.SetInt("ID", 0);
}