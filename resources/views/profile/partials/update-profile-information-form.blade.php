<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>




   <div x-data="{openModal = false}" class="flex flex-col-reverse py-4 sm:py-0  sm:flex-row flex-wrap">
        {{--Edit profile information  --}}
        <div class="basis-1/2 ">
            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('patch')

                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>

                    @if (session('status') === 'profile-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600 dark:text-gray-400"
                        >{{ __('Saved.') }}</p>
                    @endif
                </div>
            </form>
        </div>

        {{-- Change profile image --}}
        <div class="basis-1/2">
            <div class="flex flex-col gap-y-8">
                <div class="basis-1/2">
                    <img
                        id="image-profile-info"
                        class="w-32 h-32 xl:w-40 xl:h-40 rounded-full mx-auto object-cover"
                        src="{{ !empty($user->profile_image) ? $user->profile_image : "https://cdn-icons-png.flaticon.com/512/4438/4438016.png"}}"
                        alt="" >
                </div>
                <div class="basis-1/2 text-center">
                    <x-primary-button
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'change-profile-image')"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                        </svg>
                        {{ __('change') }}
                    </x-primary-button>
                </div>

                <x-modal name="change-profile-image" focusable>
                    <div class="p-6">
                        <div class="flex ">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Change profile image') }}
                            </h2>
                        </div>
                        {{-- Content --}}
                        <div class="flex py-4 flex-col gap-3">
                            <div class="basis-1/2">
                                <img class="w-32 h-32 xl:w-40 xl:h-40 rounded-full mx-auto object-cover"
                                    src="{{ !empty($user->profile_image) ? $user->profile_image : "https://cdn-icons-png.flaticon.com/512/4438/4438016.png"}}"
                                    alt="" id="image-profile-upload">
                            </div>

                            <div class="flex justify-center opacity-0" id="feedback-update-image-profile-container">
                                <div class="w-full pb-1 px-1 text-green-500 text-center" id="feedback-content">
                                    <p >Profile image update succesfull!</p>
                                </div>
                            </div>


                            <div class="flex flex-col">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="file_input">Upload your photo:</label>
                                <div class="flex  justify-between gap-3 p-4">
                                    <input
                                        id="input-file-profile"
                                        type="file"
                                        accept="image/*"
                                        class="
                                        flex-auto
                                           items-center
                                            file:mr-4
                                            file:py-2
                                            file:px-4
                                            file:border-0
                                            file:text-sm
                                            file:uppercase
                                            file:font-semibold
                                            file:bg-gray-800
                                            file:text-white
                                            hover:file:bg-gray-700
                                            dark:bg-gray-200 border border-gray-800 rounded-md font-semibold text-xs file:dark:text-gray-800  tracking-widest dark:hover:bg-white file:focus:bg-gray-700 dark:focus:bg-white file:active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150
                                        "
                                    />
                                    <x-primary-button id="btn-update-image-profile">
                                        <svg id="save-icon" class="w-5 h-5 mr-2 block"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" >
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                        </svg>
                                        <svg id="save-spinner" class="animate-spin -ml-1 mr-2 h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>


                                        {{__('save')}}
                                    </x-primary-button>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3">
                                <p class="mr-4 mt-1 text-sm text-gray-600 dark:text-gray-400    ">Or select a predefined avatar:</p>
                                <div class="basis-1/2">
                                    <div class="flex flex-row gap-x-4 justify-evenly flex-wrap p-4 gap-4 pb-0">
                                        @php
                                            $image_profile_list = [
                                                "https://cdn-icons-png.flaticon.com/512/236/236832.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219989.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219988.png",
                                                "https://cdn-icons-png.flaticon.com/512/706/706830.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219954.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219958.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219969.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219970.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219956.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219974.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219957.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219960.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219963.png",
                                                "https://cdn-icons-png.flaticon.com/512/706/706821.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219971.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219959.png",
                                                "https://cdn-icons-png.flaticon.com/512/706/706823.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219972.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219968.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219983.png",
                                                "https://cdn-icons-png.flaticon.com/512/219/219977.png",
                                                "https://cdn-icons-png.flaticon.com/512/706/706844.png",
                                                "https://cdn-icons-png.flaticon.com/512/201/201634.png",
                                                "https://cdn-icons-png.flaticon.com/512/3135/3135789.png",
                                                "https://cdn-icons-png.flaticon.com/512/706/706807.png",
                                                "https://cdn-icons-png.flaticon.com/512/147/147135.png",
                                                "https://cdn-icons-png.flaticon.com/512/706/706797.png",

                                            ]
                                        @endphp

                                        @foreach ($image_profile_list as $image )
                                            <div class="basis cursor-pointer">
                                                <img class="w-12 h-12 rounded-full mx-auto"
                                                    src="{{$image}}" alt="profile_image"
                                                    onclick="changeProfileImage('{{$image}}')">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                {{ __('Back') }}
                            </x-secondary-button>
                        </div>
                    </div>
                </x-modal>
            </div>
        </div>
   </div>
</section>
