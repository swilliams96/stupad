            <!-- SIDEBAR -->
            <div class="col-lg-3 leftsidebar">
                <div id="sidebar">
                    <h1><i class="fa fa-bars fa-pad-10"></i>Dashboard</h1>
                    <ul>
                        {!! Auth::user()->landlord ? ($page == 'mylistings' ? '<li class="currentpage"><i class="fa fa-th-list  fa-pad-5 sr-icons"></i>My Listings</li>' : '<li><a href="' . route('mylistings') . '"><i class="fa fa-th-list  fa-pad-5 sr-icons"></i>My Listings</a></li>') : '' !!}
                        {!! Auth::user()->landlord ? ($page == 'newlisting' ? '<li class="currentpage"><i class="fa fa-plus fa-pad-5 sr-icons"></i>New Listing</li>' : '<li><a href="' . route('newlisting') . '"><i class="fa fa-plus fa-pad-5 sr-icons"></i>New Listing</a></li>') : '' !!}
                        {!! $page == 'savedlistings' ? '<li class="currentpage"><i class="fa fa-heart fa-pad-5 sr-icons"></i>Saved Listings</li>' : '<li><a href="' . route('savedlistings') . '"><i class="fa fa-heart fa-pad-5 sr-icons"></i>Saved Listings</a></li>' !!}
                        {!! $page == 'messages' ? '<li class="currentpage"><i class="fa fa-comments fa-pad-5 sr-icons"></i>Messages</li>' : ('<li><a href="' . route('messages') . '"><i class="fa fa-comments fa-pad-5 sr-icons"></i>Messages</a></li>') !!}
                        {!! $page == 'profile' ? '<li class="currentpage"><i class="fa fa-cog fa-pad-5 sr-icons"></i>Edit Profile</li>' : ('<li><a href="' . route('profile') . '"><i class="fa fa-cog fa-pad-5 sr-icons"></i>Edit Profile</a></li>') !!}
                    </ul>
                </div>
            </div>
