<x-app-layout>
    <x-slot name="header">

            @if ($user->id == Auth::user()->id)
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('My posts') }}
                </h2>
            @else


                <div class="flex items-center gap-4">
                    {{-- <div>
                        <img class="w-8 h-8 rounded-full object-cover"
                            src="{{$user->profile_image }}" alt=""
                        >
                    </div> --}}
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        Post's {{$user->name}}
                    </h2>

                    <div class="ml-auto pr-4">
                        <div class="flex gap-4 items-center">
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
                </div>


            @endif

    </x-slot>

    @php
        $isUserPost = ($user->id == Auth::user()->id) ? true : false;
    @endphp


    <x-containers.main-container>
        {{-- Left section --}}
        <x-containers.left-section>
            @include('post.post-profile-information')
        </x-containers.left-section>

        {{-- Main section --}}
        <x-containers.main-section>
            {{-- Create post --}}
            @if ($user->id == Auth::user()->id)
                {{-- Create post  --}}
                <div class="py-0 pb-6">
                    {{-- <div class=" mx-auto xs:px-1 sm:px-6 lg:px-60 xl:px-[35rem] "> --}}
                    <div class="">
                        <div class="relative">

                            <div class=" divide-y divide-slate-400/20 rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10">
                                {{-- User information --}}
                                <div class="flex  gap-4 p-4 pl-4">
                                    {{-- User profile image --}}
                                    {{-- <div class="hidden sm:flex flex-col justify-start min-w-[2rem]">
                                        <div>
                                            <img class="w-8 h-8 rounded-full object-cover"
                                                src="{{$user->profile_image }}" alt=""
                                            >
                                        </div>
                                    </div> --}}

                                    {{-- Post's content --}}
                                    <div class="basis flex-auto">
                                        {{-- Input post --}}
                                        <form method="POST" action="{{ route('post.store') }}"  id="form-create-post" enctype="multipart/form-data">
                                            @csrf
                                            @method('post')
                                            <textarea id="content" name="content" rows="1" class="block p-2.5 w-full text-base resize-none overflow-hidden text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-400 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Create a new post..."></textarea>
                                        </form>
                                        {{-- Error message --}}
                                        @if (Session::has('error'))
                                            @if (Session::get('error'))
                                                @if (Session::has('message_error'))
                                                    <div class="pt-2">
                                                        <ul class="text-sm text-red-600 space-y-1">
                                                            <li> {{Session::get('message_error')  }}</li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif

                                        {{-- Post's image --}}
                                        <div id="image-post-create-container" class="hidden py-0 w-full justify-center mt-4 bg-gray-100 rounded-lg relative">
                                            <div class="basis">
                                                <img
                                                    id="image-post-create-show"
                                                    class="w-full h-auto mx-auto object-cover rounded-lg"
                                                    src=""
                                                    alt="Post's image" >
                                            </div>
                                            {{-- Delete button --}}
                                            <x-buttons.icon-button-secondary  id="delete-image-post" class="absolute">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </x-buttons.icon-button-secondary>
                                        </div>
                                    </div>
                                </div>


                                {{-- Actions --}}
                                <div class="flex justify-end gap-3 p-2 px-4">
                                    <div class="flex flex-col justify-center mr-auto">
                                        <x-buttons.input-file-secondary :id="'image'" :form="'form-create-post'">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                            </svg>
                                        </x-buttons.input-file-secondary>
                                    </div>

                                    <div>
                                        <div id='character_counter' class="h-8 w-8 py-[4px]  text-center bg-transparent rounded-full border-2 border-red-600">
                                            0
                                        </div>
                                    </div>
                                    <div >
                                        <x-primary-button id="submit-create-form" type="submit"  form="form-create-post" >Post</x-primary-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            {{-- Post --}}
            @if (isset($posts))
                @if (count($posts) != 0)
                    <div class="flex flex-col space-y-6">
                        @foreach ($posts as $post )
                            <x-post :user="$user" :post="$post" :isUserPost="$isUserPost" :isFollowing="$isFollowing"/>
                        @endforeach
                    </div>
                @else
                    {{-- If user hasn't post  --}}
                    <div class="py-0 ">
                        {{-- <div class=" mx-auto xs:px-1 sm:px-6 lg:px-60 xl:px-96 "> --}}
                        <div class=" ">
                            <div class="relative py-4">
                                <div class=" divide-y divide-slate-400/20 rounded-lg bg-transparent leading-5 text-slate-900   ring-slate-700/10">
                                            {{-- Post's comments --}}
                                            <div class="flex items-center p-2 justify-center">
                                                    <p class="text-lg">
                                                        @if ($isUserPost)
                                                            You haven't created a post yet
                                                        @else
                                                            {{$user->name}} hasn't created a post yet
                                                        @endif

                                                        </p>
                                            </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
            @else
                <p>Error</p>
            @endif
        </x-containers.main-section>

        {{-- Right section --}}
        <x-containers.right-section>
                {{-- Content --}}
        </x-containers.right-section>
    </x-containers.main-container>

     {{-- Modal like --}}
    <x-modal name="list-likes" focusable>
        <div class="p-4 md:p-6">
            <div class="flex ">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Likes') }}
                </h2>
            </div>
            {{-- Content --}}
            <div class="flex py-4 flex-col space-y-5" id="container-likes-list-users">
             {{-- Aqui van la lista de usuarios  --}}
            </div>

            {{-- Spinner --}}
            <div class="flex justify-center py-4 hidden" id="spinner-likes-list-users">
                <svg class="animate-spin  h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>



            <div class="mt-2 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Back') }}
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
</x-app-layout>
