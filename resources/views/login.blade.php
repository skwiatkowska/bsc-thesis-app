@extends('layouts.user')

@section('title', 'Logowanie')

@section('content')

<div class="container">
    <!-- MultiStep Form -->
    <div class="container-fluid" id="grad1">
        <div class="row justify-content-center mt-0">
            <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0 mt-3 mb-2">
                <div class="card card-borderless px-0 pt-4 pb-0 mt-3 mb-3">
                    <h2><strong>Logowanie</strong></h2>
                    <div class="row">
                        <div class="col-md-12 mx-0">
                            <form id="msform" action="/logowanie" method="POST">
                                {{ csrf_field() }}

                                <fieldset>
                                    <div class="form-card pt-5">
      
                                        <input class="pt-4" type="email" name="email" placeholder="Adres e-mail" />
                                        <input class="pt-4" type="password" name="password" placeholder="Hasło" />
                                        </div>
                                
                                    <button type="submit" name="next" class="action-button">Zaloguj się</button>
                                </fieldset>
                               

                               
                            </form>
                            <p class="text-center">Nie masz konta? <a href="rejestracja">Kliknij tutaj i zarejestruj się</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection