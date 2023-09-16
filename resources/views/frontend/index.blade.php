
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
@if(Auth::user())
@else
<a href="{{URL::route('login')}}">login</a>
@endif

<div id="app">
    <passport-authorized-clients></passport-authorized-clients>
    <passport-clients></passport-clients>
</div>
<script src="/js/all.js"></script>
<script>
    Echo.channel('users')
        .listen('.login', function (e) {
        console.log(e.user);
    });
</script>
</body>