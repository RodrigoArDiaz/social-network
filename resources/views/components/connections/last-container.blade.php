@props(['title','usersList'])

<div>
    <div class="relative">
        <div
            class=" divide-y divide-slate-400/20 rounded-lg bg-white dark:bg-slate-800 text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10">

            <div class="flex flex-col p-4">
                <h2 class="text-lg text-start font-medium  text-gray-800 dark:text-gray-200 leading-tight">
                        {{$title}}
                </h2>
            </div>
            @if (count($usersList) != 0)
                @foreach ($usersList as $user )
                    <div class="py-0 ">
                        <div class=" hover:cursor-pointer" id="user-card" data-id="{{$user->id}}">
                            <div class="relative">
                                <div class=" rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10">
                                    <div class="flex items-center p-3 pl-3">
                                        <div class="basis">
                                            <img class="w-6 h-6 rounded-full mx-auto object-cover" src="{{$user->profile_image}}" alt="profile image">
                                        </div>
                                        <div class="ml-4 flex-auto">
                                            <div class="font-medium  text-[1rem]">{{$user->name}}</div>
                                        </div>
                                        <div>
                                            {{str_replace('before', 'ago', $user->pivot->created_at->diffForHumans(\Carbon\Carbon::now()))}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
