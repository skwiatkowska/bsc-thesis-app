<!DOCTYPE html>
<html>


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title')</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
        integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link href="{{ asset('css/styles-admin.css') }}" rel="stylesheet" type="text/css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
        integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous">
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"
        integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous">
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="{{PUBLIC_URL}}js/script.js"></script>


    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous">
    </script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
    </script>


    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"
        media="screen" />
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"
        integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css"
        integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <link href="{{ asset('css/jquery.dynatable.css') }}" rel="stylesheet" type="text/css">

    <!-- JS Pluging -->
    <script type="text/javascript" src="{{PUBLIC_URL}}js/jquery.dynatable.js"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <script type="text/javascript" src="{{PUBLIC_URL}}js/bootstrap-editable.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>

{{-- source: https://bootstrapious.com/p/bootstrap-sidebar?fbclid=IwAR0tjMfBrHUKDS3apz4OJX5xx1PZxC8aSgrfviPklt3KIvv5i-Fv9vDL8CY--}}

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Panel Pracownika</h3>
            </div>

            <ul class="list-unstyled components">
                <li class>
                    <a href="/pracownik">
                        <i class="fas fa-home"></i>
                        Strona startowa
                    </a>

                </li>
                <li>
                    <a href="/pracownik/rezerwacje">
                        <i class="fas fa-bookmark"></i>
                        Rezerwacje
                    </a>
                </li>
                <li>
                    <a href="/pracownik/wypozyczenia">
                        <i class="fas fa-folder-open"></i>
                        Wypożyczenia
                    </a>
                </li>

                <li>
                    <a href="#membersSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-user"></i>
                        Czytelnicy
                    </a>
                    <ul class="collapse list-unstyled" id="membersSubmenu">
                        <li>
                            <a class="sidebar-submenu-item" href="/pracownik/czytelnicy/nowy">Dodaj Czytelnika</a>
                        </li>
                        <li>
                            <a class="sidebar-submenu-item" href="/pracownik/czytelnicy">Znajdź Czytelnika</a>
                        </li>

                    </ul>
                </li>
                <li>
                    <a href="/pracownik/katalog">
                        <i class="fas fa-book"></i>
                        <strong>e-Katalog</strong>
                    </a>
                </li>
                <li>
                    <a href="/pracownik/ksiazki/nowa"><i class="fas fa-plus"></i>
                        Dodaj książkę</a>
                </li>
                <li>
                    <a href="#manageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-database"></i>
                        Zbiory
                    </a>
                    <ul class="collapse list-unstyled" id="manageSubmenu">
                        <li>
                            <a class="sidebar-submenu-item" href="/pracownik/kategorie">Kategorie</a>
                        </li>
                        <li>
                            <a class="sidebar-submenu-item" href="/pracownik/autorzy">Autorzy</a>
                        </li>
                        <li>
                            <a class="sidebar-submenu-item" href="/pracownik/wydawnictwa">Wydawnictwa</a>
                        </li>

                    </ul>
                </li>


                <li>
                    <a href="/pracownik/info">
                        <i class="fas fa-info-circle"></i>
                        Informacje
                    </a>
                </li>
            </ul>

        </nav>

        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info mr-4">
                        <i class="fas fa-align-left"></i>

                    </button>
                    <div class="title-nav">
                        <p class="font-weight-bold">@yield('title')</p>
                    </div>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-right"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">

                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/pracownik/wyloguj') }}"><i class="fa fa-power-off"></i> Wyloguj</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>



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

            @yield('content')

        </div>
    </div>
    @yield('script')


    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>


</body>

</html>