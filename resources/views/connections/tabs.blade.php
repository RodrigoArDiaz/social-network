<div class="flex justify-around pb-4">
    <!-- Navigation Links -->
    <div class="flex space-x-8  text-center" >
        <x-nav-link :href="route('posts.connections', $user->id)"   :active="request()->routeIs('posts.connections')" >
            <p class="text-md" >
                {{ __('Connections') }}
            </p>
        </x-nav-link>
    </div>

    {{--  --}}
    <div class="flex space-x-8 ">
        <x-nav-link :href="route('posts.followers', $user->id)" :active="request()->routeIs('posts.followers')">
            <p class="text-md" >
                {{ __('Followers') }}
            </p>
        </x-nav-link>
    </div>

    {{-- --}}
    <div class="flex space-x-8 ">
        <x-nav-link :href="route('posts.following', $user->id)"  :active="request()->routeIs('posts.following')">
            <p class="text-md" >
                {{ __('Following') }}
            </p>
        </x-nav-link>
    </div>
</div>
