<x-app-layout>
    <x-slot name="header">
    </x-slot>

    {{-- @php
        $isUserPost = ($user->id == Auth::user()->id) ? true : false;
    @endphp
 --}}

    <x-containers.main-container>
        {{-- Left section --}}
        <x-containers.left-section>
            {{-- @include('post.post-profile-information') --}}
        </x-containers.left-section>

        {{-- Main section --}}
        <x-containers.main-section>
            {{-- Title --}}
            <div class="py-0 pl-4 pb-4">
                <div class="">
                    <h5 class="text-lg font-medium">Notifications</h5>
                </div>
            </div>
            {{-- Users' list --}}
            <div id="notifications-list-container" class="flex flex-col space-y-2">
                {{-- {{$notifications}} --}}
                @isset($notifications)
                {{-- {{$notifications}} --}}
                    @foreach (json_decode($notifications) as $notification)
                        @php
                            $user_send = $notification->user_send;
                        @endphp
                        <x-notifications.notification-card :notification="$notification" :user_send="$user_send"/>
                    @endforeach
                @endisset

            </div>
            {{-- Spinner --}}
            {{-- <div class="pb-2 pt-4" id="container-spinner-more-results">
                <div class="flex justify-center " >
                    <svg class="animate-spin h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div> --}}

        </x-containers.main-section>

        {{-- Right section --}}
        <x-containers.right-section>
                {{-- Content --}}
        </x-containers.right-section>
    </x-containers.main-container>


</x-app-layout>
