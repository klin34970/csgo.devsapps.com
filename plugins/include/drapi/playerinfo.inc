#if defined _dynamic_class_playerinfo_
  #endinput
#endif
#define _dynamic_class_playerinfo_

methodmap PlayerInfo < Dynamic
{
	public PlayerInfo()
	{
		// First we make a new dymanic object
		Dynamic myclass = Dynamic(64, 0);

		// Next we will define all the members
		// -> We do this to force the offsets to always be in the same location
		//    over multiple instances of the same class.
		myclass.SetBool("isBan", false);
		myclass.SetString("webID", "none", 11);
		myclass.SetString("steamID64", "none", 17);
		myclass.SetString("language", "none", 3);
		return view_as<PlayerInfo>(myclass);
	}

	// Note that I use static offsets to access members.
	// -> This improves performance by caching member offsets
	// -> This is why we force the members in during the contructor
	// -> Failure to force members in the constructor will cause corruption

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

	public bool GetwebID(char[] buffer, int length)
	{
		static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
		if (offset == INVALID_DYNAMIC_OFFSET)
		{
			offset = this.GetMemberOffset("webID");
			if (offset == INVALID_DYNAMIC_OFFSET)
				SetFailState("A serious error occured in Dynamic!");
		}
		this.GetStringByOffset(offset, buffer, length);
		return true;
	}

	public void SetwebID(const char[] buffer)
	{
		static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
		if (offset == INVALID_DYNAMIC_OFFSET)
		{
			offset = this.GetMemberOffset("webID");
			if (offset == INVALID_DYNAMIC_OFFSET)
			{
				offset = this.SetString("webID", buffer);
				return;
			}
		}
		this.SetStringByOffset(offset, buffer);
	}

	public bool GetsteamID64(char[] buffer, int length)
	{
		static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
		if (offset == INVALID_DYNAMIC_OFFSET)
		{
			offset = this.GetMemberOffset("steamID64");
			if (offset == INVALID_DYNAMIC_OFFSET)
				SetFailState("A serious error occured in Dynamic!");
		}
		this.GetStringByOffset(offset, buffer, length);
		return true;
	}

	public void SetsteamID64(const char[] buffer)
	{
		static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
		if (offset == INVALID_DYNAMIC_OFFSET)
		{
			offset = this.GetMemberOffset("steamID64");
			if (offset == INVALID_DYNAMIC_OFFSET)
			{
				offset = this.SetString("steamID64", buffer);
				return;
			}
		}
		this.SetStringByOffset(offset, buffer);
	}

	public bool Getlanguage(char[] buffer, int length)
	{
		static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
		if (offset == INVALID_DYNAMIC_OFFSET)
		{
			offset = this.GetMemberOffset("language");
			if (offset == INVALID_DYNAMIC_OFFSET)
				SetFailState("A serious error occured in Dynamic!");
		}
		this.GetStringByOffset(offset, buffer, length);
		return true;
	}

	public void Setlanguage(const char[] buffer)
	{
		static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
		if (offset == INVALID_DYNAMIC_OFFSET)
		{
			offset = this.GetMemberOffset("language");
			if (offset == INVALID_DYNAMIC_OFFSET)
			{
				offset = this.SetString("language", buffer);
				return;
			}
		}
		this.SetStringByOffset(offset, buffer);
	}
}