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

                    <div class="flex flex-col py-3 text-center">
                        <h3 class="text-sm font-medium text-gray-500">Posts</h3>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{$numberOfPosts}}</h3>
                    </div>

                    <div class="flex flex-row justify-evenly py-4">
                        <div class="text-center">
                            <h3 class="text-sm font-medium text-gray-500">Followers</h3>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{$numberOfFollowers}}</h3>
                        </div>

                        <div class="text-center">
                            <h3 class="text-sm font-medium text-gray-500">Following</h3>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{$numberOfFollowing}}</h3>
                        </div>

                        <div class="text-center">
                            <h3 class="text-sm font-medium text-gray-500">Connections</h3>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{$numberOfConnections}}</h3>
                        </div>
                    </div>

                    {{-- <div class="hidden md:flex flex-col py-3 text-center">
                        <h3 class="text-sm font-medium text-gray-500">Followers</h3>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{$numberOfFollowers}}</h3>
                    </div>

                    <div class="hidden md:flex flex-col py-3 text-center">
                        <h3 class="text-sm font-medium text-gray-500">Following</h3>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{$numberOfFollowing}}</h3>
                    </div>

                    <div class="hidden md:flex flex-col py-3 text-center">
                        <h3 class="text-sm font-medium text-gray-500">Connections</h3>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{$numberOfConnections}}</h3>
                    </div> --}}



                    @if ($isUserPost)
                        <div class="flex flex-col py-3 text-center">
                            <a href="{{route('profile.edit')}}" class="flex justify-center font-medium text-sm align-middle text-indigo-500">
                                {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                </svg> --}}
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
