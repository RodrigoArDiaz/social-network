<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Connect
        </h2>
    </x-slot>

    <x-containers.main-container>
        {{-- Left section --}}
        <x-containers.left-section>
        </x-containers.left-section>

        {{-- Main section --}}
        <x-containers.main-section>
            {{-- Search users --}}
            <div class="py-0 pb-6">
                <div class="">
                    <div class="relative">
                        <div class=" divide-y divide-slate-400/20 rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10">
                            <div class="flex  gap-4 p-4 pl-4">
                                <div class="basis flex-auto">
                                    <form method="POST" action="{{ route('search') }}"  id="form-create-post" enctype="multipart/form-data">
                                        @csrf
                                        @method('post')
                                        <div class="relative text-gray-600 focus-within:text-gray-400">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                                                <button type="submit" class="p-1 focus:outline-none focus:shadow-outline">
                                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                                </button>
                                            </span>
                                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full pl-10"  required autofocus autocomplete="name"  placeholder="Search connects"/>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Result search --}}
            @isset($users)
                @if (count($users) == 0)
                    {{-- No results --}}
                    <div class="py-0 ">
                        <div class="">
                            <div class="relative">
                                <div class=" divide-y divide-slate-400/20 rounded-lg bg-transparent leading-5 text-slate-900   ring-slate-700/10">
                                            <div class="flex items-center p-2 justify-center">
                                                    <p class="text-lg">
                                                        No results found for "{{$search_key}}"
                                                    </p>
                                            </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @else
                    {{-- With results --}}
                    <div class="py-0 pl-4 pb-4">
                        <div class="">
                            <h5 class="text-lg font-medium">Results</h5>
                        </div>
                    </div>
                    {{-- Users' list --}}
                    <div id="user-list-container" class="flex flex-col space-y-4">
                        @foreach ($users as  $user)
                            @php
                                $following = count($user->followers) == 0 ? false : true;
                            @endphp
                            <div class="py-0 ">
                                <div class="" id="user-card" data-id="{{$user->id}}">
                                    <div class="relative">
                                        <div class=" divide-y divide-slate-400/20 rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10 hover:shadow">
                                            {{-- User information --}}
                                            <div class="flex items-center p-3 pl-3">
                                                {{-- User profile image --}}
                                                <div class="basis">
                                                    <img class="w-10 h-10 rounded-full mx-auto object-cover"
                                                        src="{{$user->profile_image}}" alt=""
                                                    >
                                                </div>
                                                {{-- Date create post --}}
                                                <div class="ml-4 flex-auto">
                                                    <div class="font-medium  text-[1rem]">{{$user->name}}</div>
                                                </div>
                                                {{-- Actions --}}
                                                <div class="flex-auto flex gap-6 justify-end">
                                                    @if (!$following)
                                                        <x-primary-button data-id='{{$user->id}}' id="follow-button">
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
                                                    <div id='following-chip-{{$user->id}}' class="{{ $following ? "flex" : "hidden " }}  justify-center items-center m-1  py-2 px-3 rounded-full bg-gray-100 text-black-300 ">
                                                        <div class="text-sm font-medium leading-none max-w-full flex-initial">Following</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- Spinner --}}
                    <div class="pb-2 pt-4 hidden" id="container-spinner-more-results">
                        <div class="flex justify-center " >
                            <svg class="animate-spin  h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    {{-- Show more results --}}
                    <div class="py-0 pt-4" id="container-action-more-results">
                        <div class="text-center">
                            <a href="javascript:void(0)" id="action-more-results" class="flex justify-center font-medium text-base align-middle text-indigo-500">
                                Show more results
                            </a>
                        </div>
                    </div>
                @endif
            @endisset
        </x-containers.main-section>

        {{-- Right section --}}
        <x-containers.right-section>
        </x-containers.right-section>
    </x-containers.main-container>

</x-app-layout>
