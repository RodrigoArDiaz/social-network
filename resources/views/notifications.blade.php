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
                @isset($notifications)
                    @foreach (json_decode($notifications) as $notification)
                        @php
                            $user_send = $notification->user_send;
                        @endphp
                        <x-notifications.notification-card :notification="$notification" :user_send="$user_send"/>
                    @endforeach
                @endisset

            </div>
        </x-containers.main-section>

        {{-- Right section --}}
        <x-containers.right-section>
                {{-- Content --}}
        </x-containers.right-section>
    </x-containers.main-container>


</x-app-layout>
