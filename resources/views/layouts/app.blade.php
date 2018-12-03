<html>
<head>
    <title>Event - @yield('title')</title>
    <link rel="stylesheet" href="/css/app.css">
    <!--Dynamic StyleSheets -->
    @yield('styles')
    <script src="/js/app.js"></script>
</head>
<body @yield('bodyprops')>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    @auth
        <div>Connected as <b>{{auth()->user()->name}}</b> - <a href="/logout">logout</a></div>
    @endauth
</nav>

@yield('beforecontainer')

<div class="container @yield('containerclass')" style="margin-top: 20px; margin-bottom:20px">
    @yield('content')
</div>

@yield('aftercontainer')

</body>
</html>
