<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico" />

    <title>@yield('page-title')</title>
    <style>
        body {
            overflow: auto;
        }
    </style>
    @stack('style')



</head>

<body class="nav-md">
    <div class="container-fluid body">
        @yield('main-content')
    </div>

</body>

</html>
