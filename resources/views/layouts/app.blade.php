<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])


    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        {{-- Scripts --}}
        <script src="/js/helpers/index.js"></script>
        @if (request()->routeIs('profile.edit'))
            <script src="/js/profile/index.js"></script>
        @endif
        @if (request()->routeIs('post.edit'))
            <script src="/js/posts/post-edit.js"></script>
        @endif
        @if (request()->routeIs('posts'))
            <script src="/js/posts/post-create.js"></script>
            <script src="/js/posts/post-follow.js"></script>
            <script src="/js/posts/post-unfollow.js"></script>
            <script src="/js/posts/post-like.js"></script>
            <script src="/js/posts/comment.js"></script>
            <script src="/js/posts/comment-list.js"></script>
            <script src="/js/posts/post-likes-list.js"></script>
        @endif
        @if (request()->routeIs('connect') || request()->routeIs('search'))
            <script src="/js/connect/connect-follow.js"></script>
            <script src="/js/connect/connect-search-more-results.js"></script>
        @endif

    </body>
</html>
