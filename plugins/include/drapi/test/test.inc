#if defined _dynamic_class_player_
  #endinput
#endif
#define _dynamic_class_player_

methodmap Player < Dynamic
{
	public Player()
	{
		// First we make a new dymanic object
		Dynamic myclass = Dynamic(64, 0);

		// Next we will define all the members
		// -> We do this to force the offsets to always be in the same location
		//    over multiple instances of the same class.
		myclass.SetInt("ID", 0);
		return view_as<Player>(myclass);
	}

	// Note that I use static offsets to access members.
	// -> This improves performance by caching member offsets
	// -> This is why we force the members in during the contructor
	// -> Failure to force members in the constructor will cause corruption

	property int ID
	{
		public get()
		{
			static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
			if (offset == INVALID_DYNAMIC_OFFSET)
			{
				offset = this.GetMemberOffset("ID");
				if (offset == INVALID_DYNAMIC_OFFSET)
					SetFailState("A serious error occured in Dynamic!");
			}
			return this.GetIntByOffset(offset);
		}
		public set(int value)
		{
			static DynamicOffset offset = INVALID_DYNAMIC_OFFSET;
			if (offset == INVALID_DYNAMIC_OFFSET)
			{
				offset = this.GetMemberOffset("ID");
				if (offset == INVALID_DYNAMIC_OFFSET)
				{
					offset = this.SetInt("ID", value);
					return;
				}
			}
			this.SetIntByOffset(offset, value);
		}
	}
}