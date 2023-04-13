@props(['user','post', 'isUserPost','isFollowing'])

<div class="py-0 ">
    <div class="">
        <div class="relative">
            <div class=" divide-y divide-slate-400/20 rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10">
                 {{-- User information --}}
                 <div class="flex items-center p-4 pl-4">
                    {{-- User profile image --}}
                    <div class="basis min-w-[40px]">
                        <img class="w-8 h-8 rounded-full mx-auto object-cover"
                            src="{{$user->profile_image}}" alt=""
                        >
                    </div>

                    {{-- Date create post --}}
                    <div class="ml-4 flex-auto">
                        <div class="font-semibold ">
                            <a href="{{route('posts',$user->id)}}">
                                {{$user->name}}
                            </a>
                        </div>
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
                @if ($isFollowing || $isUserPost) {{-- Solo puede comentar si se esta siguiendo al usuario autor del post--}}
                    <div class="flex items-center gap-4 p-4 py-2 pl-4">
                        <div class="hidden md:flex basis">
                            <img class="w-8 h-8 rounded-full mx-auto object-cover"
                                src="{{Auth::user()->profile_image}}" alt="user's  profile image"
                            >
                        </div>
                        <div class="basis flex-auto">
                            <form method="POST"
                                {{-- action="{{ route('comment.store') }}"   --}}
                                id="form-comment-post-{{$post->id}}" class="forms-comment">
                                @csrf
                                @method('POST')
                                <textarea id="content-comment-{{$post->id}}" name="content_comment" rows="1" class="block p-2.5 w-full text-base resize-none overflow-hidden text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-400 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Comment"></textarea>
                                <input type ='text' name="post_id" id="post_id" value="{{$post->id}}" hidden/>
                            </form>
                        </div>
                        <div class="basis flex">
                            <div >
                                <x-primary-button id="submit-comment-form-{{$post->id}}" type="submit"  form="form-comment-post-{{$post->id}}" >
                                    <svg id="comment-button-spinner-{{$post->id}}" class="animate-spin h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <svg id="comment-button-{{$post->id}}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5s h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                    </svg>
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex justify-center gap-4 p-4 py-2 pl-4">
                        <div class="flex basis text-center">
                            <p>Follow {{$user->name}} to comment on this post</p>
                        </div>
                    </div>
                @endif

                {{-- Post's comments --}}
                <div class="relative  overflow-hidden   rounded-b-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10">
                    <div class="flex justify-evenly gap-4 border-none p-2">
                        {{-- Like button --}}
                        <div class="flex items-center">
                            @php
                                $userLikeToPostActive = '';
                                if (count($post->likes) != 0) { /*likes: si esta vacio indica que el autenticado no likeo el post*/
                                    $userLikeToPostActive = 'text-red-500';
                                }
                            @endphp
                            <x-buttons.icon-button-secondary class="{{$userLikeToPostActive}} border-opacity-0 shadow-none flex" id="button-like-post" data="{{$post->id}}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </x-buttons.icon-button-secondary>
                            {{-- Ver likes --}}
                            <button class="show-likes-list border-opacity-0 shadow-none flex bg-transparent
                                p-2 border rounded-full right-3 top-2  active:text-gray-500
                                items-center dark:bg-gray-800  border-gray-300 dark:border-gray-500  font-semibold
                                text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest  hover:bg-gray-50
                                dark:hover:bg-gray-700 active:outline-none active:ring-2 active:ring-indigo-500 active:ring-offset-2
                                dark:active:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 z-10"
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'list-likes')"
                                data-id="{{$post->id}}">
                                <p class="show-likes-user text-base text-gray-700 font-medium w-6 h-6" id="amount-likes-post-{{$post->id}}">
                                    @php
                                        $numberOfLikes = '';
                                        if ($post->likes_count != 0) {
                                            $numberOfLikes = $post->likes_count;
                                        }
                                    @endphp
                                    {{$numberOfLikes}}
                                </p>
                            </button>
                        </div>


                        {{-- Show comments   --}}
                        <div class="flex items-center">
                            <x-buttons.icon-button-secondary class="show-comments border-opacity-0 shadow-none flex lowercase text-sm" data-id="{{$post->id}}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                </svg>

                            </x-buttons.icon-button-secondary>
                            <p class="text-base text-gray-700 font-medium px-4" id="amount-comments-post-{{$post->id}}">
                                @if ($post->comments_count > 0)
                                    {{$post->comments_count}}
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Comments list --}}
                    <div id="container-comments-{{$post->id}}"  class="bg-white overflow-hidden transition-all duration-500 max-h-0 border-none border-transparent  peer-checked:border peer-checked:border-gray-900">
                        {{-- Aqui van los comentarios --}}
                    </div>

                    {{-- Spinner load comments --}}
                    <div id="spinner-container-comments-{{$post->id}}" class="hidden justify-center pb-4" >
                        <svg class="animate-spin  h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    {{-- Show more comments  --}}
                    <div class="show-more-comments pb-4 hidden" id="container-action-more-results-{{$post->id}}" data-id="{{$post->id}}" data-page='2'>
                        <div class="text-center">
                            <a href="javascript:void(0)" id="action-more-results-{{$post->id}}" class="flex justify-center font-medium text-sm align-middle text-indigo-500">
                                Show more comments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




