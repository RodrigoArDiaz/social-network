@props(['notification', 'user_send'])



<div class="notification-card py-2 cursor-pointer rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10" data-id="{{$notification->id}}">
    <a href='{{$notification->route_redirect}}'>
    {{-- <a href="#"> --}}
        <div class="flex items-center px-2">
            <img class="w-8 h-8 rounded-full mx-auto object-cover" src="{{$user_send->profile_image}}" alt="">
            <p class="text-base flex-auto px-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline-flex">
                    @switch($notification->type)
                        @case("PL")
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            @break

                        @case("PC")
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                            @break

                        @case("UF")
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"></path>
                            @break

                        @case("UC")
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"></path>
                            @break

                        @default
                    @endswitch
                </svg>
                @switch($notification->type)
                    @case("PL")
                        A <b>{{$user_send->name}}</b> le gustó tu post.
                        @break

                    @case("PC")
                        <b>{{$user_send->name}}</b> comentó tu post.
                        @break

                    @case("UF")
                        <b>{{$user_send->name}}</b> comenzó a seguirte.
                        @break

                    @case("UC")
                        Tu y <b>{{$user_send->name}}</b> ahora estan conectados.
                        @break

                    @default
                @endswitch
            </p>
        </div>
        <div class="flex items-center justify-end">
            @if ($notification->state == 'U')
                <div class="h-2 w-2 bg-green-500 rounded-full flex-inline"></div>
            @else
                <div class="h-2 w-2 bg-gray-400 rounded-full flex-inline"></div>
            @endif
            <p class="px-2 text-xs text-right">
                {{$notification->time_diference}}
            </p>
        </div>
    </a>
</div>
