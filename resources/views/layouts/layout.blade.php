<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>@yield('title')</title>

      <!-- jQuery -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- Bootstrap Core CSS -->
    <link href="{{PUBLIC_URL}}css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Core JavaScript -->
    <script src="{{PUBLIC_URL}}js/bootstrap.min.js"></script>
    <!-- Custom CSS -->
    <link href="{{PUBLIC_URL}}css/app.css" rel="stylesheet">

  

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"
        media="screen" />
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{PUBLIC_URL}}js/script.js"></script>



</head>

<body>
    <nav class="navbar top-nav">

        <p class="ml-auto font-weight-bold pt-1">
            <i class="fa fa-phone mr-2"></i>+ 01 234 567 89</p>
        
        </nav>

    <nav class="navbar navbar-dark navbar-expand-lg">
		
        <a class="navbar-brand" href="#"> Biblioteka Publiczna </a>
    
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>
    
        <div class="collapse navbar-collapse" id="mainmenu">
        
            <ul class="navbar-nav mr-auto">            
                <li class="nav-item px-2">
                    <a class="nav-link" href="/"> Strona główna </a>
                </li>                                
                <li class="nav-item px-2">
                    <a class="nav-link" href="pierwsze-kroki"> Pierwsze kroki </a>
                </li>                
                <li class="nav-item px-2">
                    <a class="nav-link font-weight-bold" href="#"> e-Katalog </a>
                </li>                
                <li class="nav-item px-2">
                    <a class="nav-link" href="godziny-otwarcia"> Godziny otwarcia </a>
                </li>
                <li class="nav-item px-2">
                    <a class="nav-link" href="kontakt"> Kontakt </a>
                </li>
            
            </ul>
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle font-weight-bold" href="#" id="navbardrop" data-toggle="dropdown">
        Moje konto
      </a>
      <div class="dropdown-menu">
        <a class="dropdown-item py-2" href="logowanie">Logowanie</a>
        <a class="dropdown-item py-2" href="rejestracja">Rejestracja</a>
      </div>
    </li>
         
              </ul>
    
        
        </div>
    
    </nav>

    <div class="container">

        <div class="row">
            {{--<div class="box">--}}

            
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
                        {!! implode('', $errors->all('<li><i class="fas fa-exclamation-triangle mr-2"></i>:message</li>')) !!}
                    </ul>
                </div>
    
                @endif
    

            {{--</div>--}}
        </div>
    </div>



    @yield('content')


    <!-- Footer -->
    <footer class="page-footer font-small text-light mdb-color pt-2">

        <!-- Footer Links -->
        <div class="container text-center text-md-left">

            <!-- Footer links -->
            <div class="row text-center text-md-left mt-3 pb-2">
                <hr class="w-100 clearfix d-md-none">

                <!-- Grid column -->
                <div class="col-md-9 mx-auto mt-3">
                    <h6 class="text-uppercase mb-3 font-weight-bold">BIBLIOTEKA PUBLICZNA W KRAKOWIE</h6>
                    <p>
                        <i class="fa fa-home mr-3"></i>Aleja Adama Mickiewicza 30, 30-059 Kraków</p>
                    <p>
                        <i class="fa fa-envelope mr-3"></i>kontakt@bibliotekakrakow.com</p>
                    <p>
                        <i class="fa fa-phone mr-3"></i>+ 01 234 567 89</p>
                </div>
                <!-- Grid column -->


                <hr class="clearfix mt-1 d-md-none">

                <!-- Grid column -->
                <div class="col-md-3 mx-auto mt-3">
                    <br />
                    <div class="text-center">
                        <ul class="list-unstyled list-inline">
                            <li class="list-inline-item">
                                <a class="btn-floating btn-sm rgba-white-slight mx-1">
                                    <i class="fa fa-facebook fa-2x"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a class="btn-floating btn-sm rgba-white-slight mx-1">
                                    <i class="fa fa-twitter fa-2x"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a class="btn-floating btn-sm rgba-white-slight mx-1">
                                    <i class="fa fa-instagram fa-2x"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a class="btn-floating btn-sm rgba-white-slight mx-1">
                                    <i class="fa fa-linkedin fa-2x"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Grid column -->

            </div>
            <!-- Footer links -->

            <hr class="mt-0">

            <!-- Grid row -->
            <div class="row d-flex align-items-center">

                <!-- Grid column -->
                <div class="col-12">
                    <!--Copyright-->
                    <p class="text-center">© 2020 Sylwia Kwiatkowska. Wszystkie prawa zastrzeżone.</p>

                </div>
                <!-- Grid column -->




            </div>
            <!-- Grid row -->

        </div>
        <!-- Footer Links -->

    </footer>
    <!-- Footer -->



</body>


</html>