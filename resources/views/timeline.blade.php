<x-app-layout>
    <x-slot name="header">
    </x-slot>


    <x-containers.main-container>
        {{-- Left section --}}
        <x-containers.left-section>
        </x-containers.left-section>
        {{-- Main section --}}
        <x-containers.main-section>
            {{-- Post --}}
            {{-- {{$posts}} --}}
            {{-- Post --}}
            @if (isset($posts))
                @if (count($posts) != 0)
                    <div class="flex flex-col space-y-6" id="container-posts-timeline">
                        @foreach ($posts as $post )
                            @php
                                $user = $post->user;
                                $isUserPost = false;
                                $isFollowing = true

                            @endphp
                            {{-- {{$user}} --}}
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
