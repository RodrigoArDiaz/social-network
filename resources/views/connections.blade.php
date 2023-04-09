<x-app-layout>
    <x-slot name="header">
        @if (request()->routeIs('posts.connections'))
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Connections') }}
            </h2>
        @endif

        @if (request()->routeIs('posts.followers'))
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Followers') }}
            </h2>
        @endif

        @if (request()->routeIs('posts.following'))
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Following') }}
            </h2>
        @endif

        @php
            $isUserPost = ($user->id == Auth::user()->id) ? true : false;
        @endphp
    </x-slot>

    <x-containers.main-container>
        {{-- Left section --}}
        <x-containers.left-section>
            @include('post.post-profile-information')
        </x-containers.left-section>

        {{-- Main section --}}
        <x-containers.main-section>
            {{-- Tabs --}}
            @include('connections.tabs')

            {{-- Users --}}
            <div id="user-list-container" class="flex flex-col space-y-4" data-id="{{$user->id}}">
                @foreach ($users as  $user)
                    <div class="py-0 ">
                        <div class=" hover:cursor-pointer" id="user-card" data-id="{{$user->id}}">
                            <div class="relative">
                                <div class=" divide-y divide-slate-400/20 rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10 hover:shadow">
                                    {{-- User information --}}
                                    <div class="flex items-center p-3 pl-3">
                                        {{-- User profile image --}}
                                        <div class="basis">
                                            <img class="w-10 h-10 rounded-full mx-auto object-cover" src="{{$user->profile_image}}" alt="profile image">
                                        </div>
                                        {{-- Date create post --}}
                                        <div class="ml-4 flex-auto">
                                            <div class="font-medium  text-[1rem]">{{$user->name}}</div>
                                        </div>
                                        {{-- Actions --}}
                                        @if (request()->routeIs('posts.connections') || request()->routeIs('posts.following'))
                                            @if ($isUserPost)
                                                <div class="flex-auto flex gap-6 justify-end">
                                                        <x-primary-button data-id='{{$user->id}}' id="unfollow-button-post">
                                                            <svg id="unfollow-icon-{{$user->id}}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                                            </svg>
                                                            <svg id="unfollow-spinner-{{$user->id}}" class="animate-spin -ml-1 mr-2 h-4 w-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                            Unfollow
                                                        </x-primary-button>
                                                </div>
                                            @endif
                                        @endif

                                        @if (request()->routeIs('posts.followers'))
                                            @if ($isUserPost)
                                                @if ($user->following)
                                                    <div id='following-chip' class="flex justify-center items-center m-1  py-2 px-3 rounded-full bg-gray-100 text-black-300">
                                                        <div class="text-sm font-medium leading-none max-w-full flex-initial">Following</div>
                                                    </div>
                                                @else
                                                    <div id='following-chip-{{$user->id}}' class="hidden  justify-center items-center m-1  py-2 px-3 rounded-full bg-gray-100 text-black-300">
                                                        <div class="text-sm font-medium leading-none max-w-full flex-initial">Following</div>
                                                    </div>
                                                    <x-primary-button data-id='{{$user->id}}' id="follow-button-post" >
                                                        <svg id="follow-icon-{{$user->id}}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                                        </svg>
                                                        <svg id="follow-spinner-{{$user->id}}" class="animate-spin -ml-1 mr-2 h-4 w-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Follow
                                                    </x-primary-button>
                                                @endif
                                            @endif
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-containers.main-section>

        {{-- Right section --}}
        <x-containers.right-section>
                {{-- Content --}}
        </x-containers.right-section>
    </x-containers.main-container>


</x-app-layout>
