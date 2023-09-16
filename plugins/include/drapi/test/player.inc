#if defined _dynamic_class_player_
  #endinput
#endif
#define _dynamic_class_player_

Dynamic myclass[MAXPLAYERS + 1];

methodmap Player < Dynamic
{
    public Player(int client){
        return view_as<Player>(myclass[client]);
    }

    public void init(int client){
        //PrintToServer("%d init %d", client, myclass[client].IsValid);
        myclass[client] = Dynamic(64, 0);
        char sSetName[32];
        Format(sSetName, sizeof(sSetName), "drapi_users_%d", client);
        myclass[client].SetName(sSetName);
        PrintToServer("%s SetName %d %s", TAG, client, sSetName);
        //PrintToServer("%d init %d", client, myclass[client].IsValid);
    }

    public void clear(int client){
        myclass[client].SetBool("isBan", false);
        myclass[client].SetString("WebID", "none", 64);
        myclass[client].SetString("SteamID64", "none", 64);
        myclass[client].SetString("lastConnect", "", 64);
        //PrintToServer("%d clear", client);
    }
    public void dispose(int client){
        myclass[client].Dispose();
        myclass[client] = INVALID_DYNAMIC_OBJECT;
        //PrintToServer("%d Dispose", client);
    }

    // Note that I use static offsets to access members.
    // -> This improves performance by caching member offsets
    // -> This is why we force the members in during the contructor
    // -> Failure to force members in the constructor will cause corruption
    public bool GetWebID(char[] buffer, int length)
    {
        static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
        if (offset == INVALID_DYNAMIC_OFFSET)
        {
            offset = this.GetMemberOffset("WebID");
            if (offset == INVALID_DYNAMIC_OFFSET)
                SetFailState("A serious error occured in Dynamic!");
        }
        this.GetStringByOffset(offset, buffer, length);
        return true;
    }

    public void SetWebID(const char[] buffer)
    {
        static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
        if (offset == INVALID_DYNAMIC_OFFSET)
        {
            offset = this.GetMemberOffset("WebID");
            if (offset == INVALID_DYNAMIC_OFFSET)
            {
                offset = this.SetString("WebID", buffer);
                return;
            }
        }
        this.SetStringByOffset(offset, buffer);
    }

    public bool GetSteamID64(char[] buffer, int length)
    {
        static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
        if (offset == INVALID_DYNAMIC_OFFSET)
        {
            offset = this.GetMemberOffset("SteamID64");
            if (offset == INVALID_DYNAMIC_OFFSET)
                SetFailState("A serious error occured in Dynamic!");
        }
        this.GetStringByOffset(offset, buffer, length);
        return true;
    }

    public void SetSteamID64(const char[] buffer)
    {
        static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
        if (offset == INVALID_DYNAMIC_OFFSET)
        {
            offset = this.GetMemberOffset("SteamID64");
            if (offset == INVALID_DYNAMIC_OFFSET)
            {
                offset = this.SetString("SteamID64", buffer);
                return;
            }
        }
        this.SetStringByOffset(offset, buffer);
    }

    property bool isBan
    {
        public get()
        {
            static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
            if (offset == INVALID_DYNAMIC_OFFSET)
            {
                offset = this.GetMemberOffset("isBan");
                if (offset == INVALID_DYNAMIC_OFFSET)
                    SetFailState("A serious error occured in Dynamic!");
            }
            return this.GetBoolByOffset(offset);
        }
        public set(bool value)
        {
            static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
            if (offset == INVALID_DYNAMIC_OFFSET)
            {
                offset = this.GetMemberOffset("isBan");
                if (offset == INVALID_DYNAMIC_OFFSET)
                {
                    offset = this.SetBool("isBan", value);
                    return;
                }
            }
            this.SetBoolByOffset(offset, value);
        }
    }

    public bool GetlastConnect(char[] buffer, int length)
    {
        static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
        if (offset == INVALID_DYNAMIC_OFFSET)
        {
            offset = this.GetMemberOffset("lastConnect");
            if (offset == INVALID_DYNAMIC_OFFSET)
                SetFailState("A serious error occured in Dynamic!");
        }
        this.GetStringByOffset(offset, buffer, length);
        return true;
    }

    public void SetlastConnect(const char[] buffer)
    {
        static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
        if (offset == INVALID_DYNAMIC_OFFSET)
        {
            offset = this.GetMemberOffset("lastConnect");
            if (offset == INVALID_DYNAMIC_OFFSET)
            {
                offset = this.SetString("lastConnect", buffer);
                return;
            }
        }
        this.SetStringByOffset(offset, buffer);
    }


}