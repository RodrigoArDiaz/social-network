<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Post') }}
        </h2>
    </x-slot>



    <div class="py-0 ">
        <div class=" mx-auto xs:px-1 sm:px-6 lg:px-60 xl:px-[35rem]">
            <div class="relative py-4">
                <div class=" divide-y divide-slate-400/20 rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10">
                    {{-- User information --}}
                    <div class="flex items-center p-4 pl-4">
                        {{-- User profile image --}}
                        <div class="basis">
                            <img class="w-8 h-8 rounded-full mx-auto"
                                src="{{$user->profile_image}}" alt=""
                            >
                        </div>

                        {{-- Date create post --}}
                        <div class="ml-4 flex-auto">
                            <div class="font-semibold">{{$user->name}}</div>
                            <div class="mt-1 text-slate-700">
                                <i>Post</i> : {{  $post->created_at->toDayDateTimeString()}}
                                @if (! $post->created_at->eq($post->updated_at ))
                                -  <i>(Edit)</i> : {{$post->updated_at->toDayDateTimeString()}}
                                @endif

                            </div>
                        </div>

                    </div>

                    {{-- Post's content --}}
                    <div class="flex items-center p-4 border-t-0">
                        <div class="basis w-full">
                              {{-- Input post --}}
                              <form method="POST" action="{{ route('post.update',$post->id) }}"  id="form-edit-post" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <textarea id="content_edit" name="content_edit" rows="1" class="block p-2.5 w-full text-base resize-none overflow-hidden text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-400 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Edit a new post...">{{$post->content}}</textarea>
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
                        </div>
                    </div>

                    {{-- Post's image --}}
                    {{-- @if (strlen($post->image) !== 0) --}}
                    @php
                        $hidden = (strlen($post->image) !== 0) ? 'flex' : 'hidden';
                    @endphp
                        <div id="image-post-edit-container"   class="{{$hidden}}  items-center p-4">
                            <div  class="py-0 w-full justify-center bg-gray-100 rounded-lg relative">
                                <div class="basis">
                                    <img
                                        id="image-post-edit-show"
                                        class="w-full h-auto mx-auto object-cover rounded-lg"
                                        src="{{$post->image}}"
                                        alt="Post's image" >
                                </div>

                                {{-- Delete button --}}
                                <x-buttons.icon-button-secondary  id="delete-image-post-edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </x-buttons.icon-button-secondary>
                                {{-- Input hidden  que indica si hay que eliminar o no la imagen anterior --}}
                                <input type="checkbox" id='no-image' name="no-image" class="hidden" form="form-edit-post"/>
                            </div>
                        </div>
                    {{-- @endif --}}

                    {{-- Actions --}}
                    <div class="flex justify-end gap-3 p-2 px-4">
                        <div class="flex flex-col justify-center mr-auto">
                            <x-buttons.input-file-secondary :id="'image_edit'" :form="'form-edit-post'">
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
                        {{-- <div >
                            <x-primary-button id="submit-create-form" type="submit"  form="form-create-post" >Post</x-primary-button>
                            </div> --}}
                        <div class="ml-4">
                            <form method="GET" action="{{ route('posts',Auth::user()->id) }}"  id="form-back">
                            </form>
                            <x-secondary-button type="submit" form="form-back">Cancel</x-primary-button>
                        </div>
                        <div>
                            <x-primary-button type="submit" id="submit-edit-post" form="form-edit-post">Save</x-primary-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
