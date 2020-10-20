<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Logowanie - panel pracownika</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
        integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link href="{{ asset('css/styles-admin.css') }}" rel="stylesheet" type="text/css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
        integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous">
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"
        integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous">
    </script>
    <!-- Include the above in your HEAD tag -->

</head>

{{-- source: https://bootsnipp.com/snippets/MR9Al --}}

<body class="login-body">
    @if (\Session::has('success'))
    <div class="alert alert-success">
        <ul class="ul-alert">
            <li><i class="fas fa-check-circle mr-2"></i>{!! \Session::get('success') !!}</li>
        </ul>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="ul-alert">

            {!! implode('', $errors->all('<li><i class="fas fa-exclamation-triangle mr-2"></i>:message</li>'))
            !!}
        </ul>
    </div>

    @endif
    <div class="login-page">
        <div class="form">
            <h2 class="mb-5"><strong>Panel pracownika</strong></h2>
            <form class="login-form" action="/pracownik/logowanie" method="POST">
                {{ csrf_field() }}
                <input type="text" name="email" placeholder="Login" />
                <input type="password" name="password" placeholder="Hasło" />
                <button type="submit" class="mt-5">Zaloguj się</button>
            </form>
        </div>
    </div>
</body>


</html>