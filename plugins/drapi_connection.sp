#pragma semicolon 1

#define TAG "[CONNECTIONS] -"
#define PLUGIN_VERSION "1.0.0"
#define CVARS FCVAR_SPONLY|FCVAR_REPLICATED|FCVAR_NOTIFY
#define DEFAULT_FLAGS FCVAR_NOTIFY

#undef REQUIRE_PLUGIN
#include <csgocolors>
#include <drapi/drapi>

#pragma newdecls required

#define PAGINATION 30

Dynamic Dconnection[MAXPLAYERS+1][PAGINATION];

public Plugin myinfo ={
    name = "drapi_connections",
    author = "Dr. Api",
    description = "",
    version = PLUGIN_VERSION,
    url = "https://csgo.devsapps.com"
}

public APLRes AskPluginLoad2(Handle myself, bool late, char[] error, int err_max){
    RegPluginLibrary("drapi_users");
}

public void OnPluginStart(){
    RegConsoleCmd("sm_connection", RegConsoleCmdConnection, "Get Last Connection");
    RegConsoleCmd("sm_co", RegConsoleCmdConnection, "Get Last Connection");
}

public void OnClientDisconnect(int client){
    for (int i = 0; i < PAGINATION; i++) {
        if(Dconnection[client][i].IsValid){
            //PrintToServer("%s Dconnection %d %d", TAG, client, Dconnection[client][i]);
            Dconnection[client][i].Dispose();
            Dconnection[client][i] = INVALID_DYNAMIC_OBJECT;
        }
    }
}

public Action RegConsoleCmdConnection(int client, int args){
    httpClient(client).Get("connections/me", OnReceived, client);
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
    JSONObject users = view_as<JSONObject>(response.Data);

    JSONObject meta = view_as<JSONObject>(users.Get("meta"));

    Menu menu = new Menu(MenuConnection, MenuAction_Cancel|MenuAction_End);
    menu.SetTitle("Connections %d", meta.GetInt("total"));

    JSONArray data = view_as<JSONArray>(users.Get("data"));
    int numUser = data.Length;

    //PrintToServer("%s numUser %d", TAG, numUser);

    JSONObject user;
    char created_at[64], ip[45], support[8], country[3];
    for (int i = 0; i < numUser; i++) {
        Dconnection[client][i] = Dynamic();

    	user = view_as<JSONObject>(data.Get(i));

    	user.GetString("created_at", created_at, sizeof(created_at));
    	user.GetString("ip", ip, sizeof(ip));
    	user.GetString("support", support, sizeof(support));
    	user.GetString("country", country, sizeof(country));

    	//PrintToServer("%s index %d created_at %s ip %s", TAG, i, created_at, ip);

        Dconnection[client][i].SetString("created_at", created_at);
    	Dconnection[client][i].SetString("ip", ip);
    	Dconnection[client][i].SetString("support", support);
    	Dconnection[client][i].SetString("country", country);

        char index[3];
        IntToString(i, index, sizeof(index));
        //PrintToServer("%s IntToString %s", TAG, index);
    	menu.AddItem(index, created_at);

    	delete user;
    }
    delete data;

    menu.ExitButton = true;
    menu.Display(client, 20);
}
public int MenuConnection(Menu menu, MenuAction action, int param1, int param2){
    switch(action){
	    case MenuAction_End:{
            delete menu;
        }
        case MenuAction_Cancel:{
            for (int i = 0; i < PAGINATION; i++) {
                if(Dconnection[param1][i].IsValid){
                    //PrintToServer("%s MenuAction_Cancel Dconnection %d %d", TAG, param1, Dconnection[param1][i]);
                    Dconnection[param1][i].Dispose();
                    Dconnection[param1][i] = INVALID_DYNAMIC_OBJECT;
                }
            }
        }
        case MenuAction_Select:{
            char created_at[64], ip[45], support[8], country[3];
            Dconnection[param1][param2].GetString("ip", ip, sizeof(ip));
            Dconnection[param1][param2].GetString("created_at", created_at, sizeof(created_at));
            Dconnection[param1][param2].GetString("support", support, sizeof(support));
            Dconnection[param1][param2].GetString("country", country, sizeof(country));
            PrintToChat(param1, "ip: %s, support: %s, country: %s, created_at: %s", ip, support, country, created_at);
            for (int i = 0; i < PAGINATION; i++) {
                if(Dconnection[param1][i].IsValid){
                    //PrintToServer("%s MenuAction_Select Dispose %d %d", TAG, param1, Dconnection[param1][i]);
                    Dconnection[param1][i].Dispose();
                    Dconnection[param1][i] = INVALID_DYNAMIC_OBJECT;
                }
            }
        }
	}
}
