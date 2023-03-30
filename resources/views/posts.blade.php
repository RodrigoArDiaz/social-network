<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @if ($user->id == Auth::user()->id)
                {{ __('My posts') }}
            @else
                <div class="flex items-center gap-4">
                    <div>
                        <img class="w-8 h-8 rounded-full object-cover"
                            src="{{$user->profile_image }}" alt=""
                        >
                    </div>

                        {{$user->name}}

                </div>
            @endif
        </h2>
    </x-slot>

    @php
        $isUserPost = ($user->id == Auth::user()->id) ? true : false;
    @endphp


    {{-- Create post --}}
    @if ($user->id == Auth::user()->id)
        {{-- Create post  --}}
        <div class="py-0 ">
            <div class=" mx-auto xs:px-1 sm:px-6 lg:px-60 xl:px-[35rem] ">
                <div class="relative py-4">

                    <div class=" divide-y divide-slate-400/20 rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10">
                        {{-- User information --}}
                        <div class="flex  gap-4 p-4 pl-4">
                            {{-- User profile image --}}
                            <div class="hidden sm:flex flex-col justify-start min-w-[2rem]">
                                <div>
                                    <img class="w-8 h-8 rounded-full object-cover"
                                        src="{{$user->profile_image }}" alt=""
                                    >
                                </div>
                            </div>

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
                                    <x-buttons.icon-button-secondary  id="delete-image-post">
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
           @foreach ($posts as $post )
               <x-post :user="$user" :post="$post" :isUserPost="$isUserPost"/>
           @endforeach
       @else
           {{-- If user hasn't post  --}}
           <div class="py-0 ">
               <div class=" mx-auto xs:px-1 sm:px-6 lg:px-60 xl:px-96 ">
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

</x-app-layout>
