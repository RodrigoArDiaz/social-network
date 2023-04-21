<x-app-layout>
    <x-slot name="header">
    </x-slot>

    @php
        $isUserPost = true;
    @endphp

    <x-containers.main-container>
        {{-- Left section --}}
        <x-containers.left-section>
            @include('post.post-profile-information')
        </x-containers.left-section>
        {{-- Main section --}}
        <x-containers.main-section>
            @php
                $isUserPost = false;
                $isFollowing = true;
                $isStarredComment = false;
                if (isset($starredComment)) {
                    $isStarredComment = true;
                }else {
                    $starredComment =null;
                }
            @endphp
                <x-post :user="$user"
                        :post="$post"
                        :isUserPost="$isUserPost"
                        :isFollowing="$isFollowing"
                        :isStarredComment="$isStarredComment"
                        :starredComment="$starredComment" />

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
