            <!-- SIDEBAR -->
            <div class="col-lg-3 leftsidebar">
                <h1><i class="fa fa-cog fa-pad-5 sr-icons"></i>Settings</h1>
                <ul>
                    <li><a href=" {{ route('search') }}">Search</a></li>
                    {!! $page == 'profile' ? '<li class="currentpage">Edit Profile</li>' : ('<li><a href="' . route('profile') . '">Edit Profile</a></li>') !!}
                    {!! $page == 'messages' ? '<li class="currentpage">Messages</li>' : ('<li><a href="' . route('messages') . '">Messages</a></li>') !!}
                    {!! Auth::user()->landlord ? ($page == 'mylistings' ? '<li class="currentpage">My Listings</li>' : '<li><a href="' . route('mylistings') . '">My Listings</a></li>') : '' !!}
                    {!! Auth::user()->student ? ($page == 'savedlistings' ? '<li class="currentpage">Saved Listings</li>' : '<li><a href="' . route('savedlistings') . '">Saved Listings</a></li>') : '' !!}
                </ul>
            </div>
