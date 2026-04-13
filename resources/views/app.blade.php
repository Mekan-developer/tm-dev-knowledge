<!DOCTYPE html>
<html lang="tk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script>
            (function () {
                try {
                    var t = localStorage.getItem('theme');
                    var dark =
                        t === 'dark' ||
                        (!t && window.matchMedia('(prefers-color-scheme: dark)').matches);
                    if (dark) document.documentElement.classList.add('dark');
                    else document.documentElement.classList.remove('dark');
                } catch (e) {}
            })();
        </script>

        <title inertia>{{ config('app.name', 'DevKnowledge') }}</title>
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased dark:bg-gray-950">
        @inertia
    </body>
</html>
