@props(['user','post', 'isUserPost'])

<div class="py-0 ">
    {{-- <div class=" mx-auto xs:px-1 sm:px-6 lg:px-60 xl:px-[35rem]"> --}}
    <div class="">
        <div class="relative">
            <div class=" divide-y divide-slate-400/20 rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10">
                 {{-- User information --}}
                 <div class="flex items-center p-4 pl-4">
                    {{-- User profile image --}}
                    <div class="basis">
                        <img class="w-8 h-8 rounded-full mx-auto object-cover"
                            src="{{$user->profile_image}}" alt=""
                        >
                    </div>

                    {{-- Date create post --}}
                    <div class="ml-4 flex-auto">
                        <div class="font-semibold ">{{$user->name}}</div>
                        <div class="mt-1 text-slate-700">
                            <i>Post</i> : {{  $post->created_at->toDayDateTimeString()}}
                            @if (! $post->created_at->eq($post->updated_at ))
                               -  <i class="text-green-500">Edit</i> : {{$post->updated_at->toDayDateTimeString()}}
                            @endif

                        </div>
                    </div>


                    <!-- Menu Dropdown -->
                    @if ($isUserPost)
                        <div class="flex items-center ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                        </svg>
                                    </button>
                                </x-slot>
                                {{-- Dropdown content --}}
                                <x-slot name="content" >


                                    {{-- Edit --}}
                                    <x-dropdown-link :href="route('post.edit',$post)">
                                        <div class="inline-flex justify-center align-middle text-base gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                            </svg>
                                                Edit
                                        </div>
                                    </x-dropdown-link>

                                    {{-- Delete --}}
                                    <x-dropdown-link >
                                        <div>
                                            <form method="POST" action="{{route('post.delete', $post->id)}}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-base inline-flex gap-2 w-full px-0 py-2 text-left leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </x-dropdown-link>

                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif



                </div>

                {{-- Post's content --}}
                <div class="flex items-center p-4 border-t-0">
                    <div class="basis">
                        <p class="text-base">
                            {{-- Uso echo para mostrar como html los saltos de linea --}}
                            <?php
                            //  echo  nl2br($post->content);
                            ?>

                            {{-- {!!nl2br($post->content)!!} --}} {{-- Esta manera muestra de forma correcta pero tiene vulnerabilidad si se escribe codigo javascript--}}
                            {!!nl2br(e($post->content))!!} {{-- Esta es la manera correcta de mostrar texto con salto de lineas, sin vulnerabilidad--}}

                        </p>

                    </div>
                </div>

                {{-- Post's image --}}
                @if (strlen($post->image) !== 0)
                    <div class="flex items-center p-4">
                        <div id="image-post-create-container" class="py-0 w-full justify-center bg-gray-100 rounded-lg relative">
                            <div class="basis">
                                <img
                                    id="image-post-create-show"
                                    class="w-full h-auto mx-auto object-cover rounded-lg"
                                    src="{{$post->image}}"
                                    alt="Post's image" >
                            </div>
                        </div>
                    </div>
                @endif





                {{-- Comment --}}
                {{-- @if ($user->id != Auth::user()->id)
                    <div class="flex items-center gap-4 p-4 py-2 pl-4">

                        <div class="basis">
                            <img class="w-8 h-8 rounded-full mx-auto"
                                src="{{Auth::user()->profile_image_url}}" alt=""
                            >
                        </div>
                        <div class="basis flex-auto">

                            <form method="POST" action="{{ route('comment.store') }}"  id="form-comment-post">
                                @csrf
                                @method('POST')
                                <textarea id="content" name="content" rows="1" class="block p-2.5 w-full text-base resize-none overflow-hidden text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-400 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Comment"></textarea>
                                <input type ='text' name="post_id" id="post_id" value="{{$post->id}}" hidden/>
                            </form>
                        </div>

                        <div class="basis flex">
                            <div >
                                <x-primary-button id="submit-create-form" type="submit"  form="form-comment-post" >Comment</x-primary-button>
                            </div>
                        </div>

                    </div>
                @endif --}}



                {{-- Post's comments --}}
                <div class="relative  overflow-hidden  divide-y divide-slate-400/20 rounded-b-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10">
                    <input type="checkbox" class="peer absolute top-0 inset-x-0 w-full h-12 opacity-0 z-10 cursor-pointer" name="show-comments" id="show-comments" data-id="{{$post->id}}">
                    <div class="flex justify-center border-none py-2">

                        <button class="basis inline-flex  sw-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                               >
                                                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                            </svg>
                            <p class="text-sm">

                                {{-- @if (count($post->comments) > 0)
                                    {{count($post->comments)}} comments

                                @else
                                    Not comments
                                @endif --}}

                            </p>
                        </button>
                    </div>

                    <div id="container-comments-{{$post->id}}"  class="bg-white overflow-hidden transition-all duration-500 max-h-0 border-none  peer-checked:max-h-max  peer-checked:border peer-checked:border-gray-900">


                    </div>


                </div>


            </div>
        </div>
    </div>
</div>
