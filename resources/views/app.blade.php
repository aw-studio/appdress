<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link href="docs/css/app.css" rel="stylesheet">

</head>
<body>

    <div id="app" class="flex">
        <aside class="p-8">
            <div class="sticky top-0">
                @include('docs::partials.header')
                @include('docs::partials.nav_main')
            </div>
        </aside>
        <main class="pt-6 pb-40">
            <section id="docs">
                    @yield('content')
            </section>
        </main>

    </div>
    
</body>
</html>