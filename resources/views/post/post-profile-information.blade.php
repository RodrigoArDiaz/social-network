<section>
    <div class="">
        <div>
            <div class="relative">
                <div class=" divide-y divide-slate-400/20 rounded-lg bg-white dark:bg-slate-800 text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10">

                    <div class="flex flex-col py-4 gap-1">
                        <div class="align-center">
                            <img
                                id="image-profile-info"
                                class="w-32 h-32 rounded-full mx-auto object-cover"
                                src="{{ !empty($user->profile_image) ? $user->profile_image : "https://cdn-icons-png.flaticon.com/512/4438/4438016.png"}}"
                                alt="" >
                        </div>

                        <div >
                            <h2 class="text-xl text-center font-medium  text-gray-800 dark:text-gray-200 leading-tight">{{$user->name}}</h2>
                        </div>
                    </div>


                @if ($user->id != Auth::user()->id)

                    @php
                        $followShow = '';
                        $unfollowShow = '';
                        $connectedShow = 'hidden';
                        if ($isFollowing) $followShow = 'hidden';
                        else $unfollowShow = 'hidden';
                        if ($isFollowing && $isFollower) {
                            $connectedShow = '';
                        }
                    @endphp

                    <div class="flex  flex-col items-center gap-4 py-4">
                        <div class="">
                            <div class="flex gap-4 items-center">


                                {{-- Show if both follow each other --}}
                                <div id="connected" class="{{$connectedShow}} rounded-full py-2 px-2 bg-gray-100 ">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                    </svg>
                                </div>

                                {{-- Show  if the user is follower --}}
                                @if ($isFollower)
                                    <div id='following-chip' class="flex justify-center items-center m-1  py-2 px-3 rounded-full bg-gray-100 text-black-300">
                                        <div class="text-sm font-medium leading-none max-w-full flex-initial">Is follower</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-center">
                            {{-- Follow button --}}
                            <x-primary-button data-id='{{$user->id}}' id="follow-button-post" class="{{$followShow}}">
                                <svg id="follow-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                </svg>
                                <svg id="follow-spinner" class="animate-spin -ml-1 mr-2 h-4 w-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Follow
                            </x-primary-button>

                            {{-- Unfollow button --}}
                            <x-primary-button class="hidden" data-id='{{$user->id}}' id="unfollow-button-post" class="{{$unfollowShow}}">
                                <svg id="unfollow-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                </svg>
                                <svg id="unfollow-spinner" class="animate-spin -ml-1 mr-2 h-4 w-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Unfollow
                            </x-primary-button>
                        </div>


                    </div>


                @endif

                    <div class="flex flex-col py-3 text-center">
                        {{-- <h3 class="text-sm font-medium text-gray-500">Posts</h3> --}}
                        <a href="{{route('posts', $user->id)}}"  class="text-sm font-medium text-gray-500">
                            {{ __('Posts') }}
                        </a>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{$numberOfPosts}}</h3>
                    </div>

                    <div class="flex flex-row justify-evenly py-4">

                        <div class="text-center">
                            {{-- <h3 class="text-sm font-medium text-gray-500">Connections</h3> --}}
                            <a href="{{route('posts.connections', $user->id)}}"  class="text-sm font-medium text-gray-500">
                                {{ __('Connections') }}
                            </a>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{$numberOfConnections}}</h3>
                        </div>

                        <div class="text-center">
                            {{-- <h3 class="text-sm font-medium text-gray-500">Followers</h3> --}}
                            <a href="{{route('posts.followers', $user->id)}}"  class="text-sm font-medium text-gray-500">
                                {{ __('Followers') }}
                            </a>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{$numberOfFollowers}}</h3>
                        </div>

                        <div class="text-center">
                            {{-- <h3 class="text-sm font-medium text-gray-500">Following</h3> --}}
                            <a href="{{route('posts.following', $user->id)}}"  class="text-sm font-medium text-gray-500">
                                {{ __('Following') }}
                            </a>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{$numberOfFollowing}}</h3>
                        </div>

                    </div>

                    @if ($isUserPost)
                        <div class="flex flex-col py-3 text-center">
                            <a href="{{route('profile.edit')}}" class="flex justify-center font-medium text-sm align-middle text-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2 mt-0.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                  </svg>

                                Edit profile
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
