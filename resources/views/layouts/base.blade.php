<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite('resources/css/app.css')
</head>
<body>
    <h1>@yield('title')</h1>
    @if (session('success'))
        <div>
            <strong>{{ session('success') }}</strong>
        </div>
    @endif

    @if (session('status'))
        <div>
            <strong>{{ session('status') }}</strong>
        </div>
    @endif

    @yield('content')
</body>
</html>
