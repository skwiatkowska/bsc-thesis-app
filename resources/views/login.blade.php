@extends('layouts.layout')

@section('title', 'Logowanie')

@section('content')

<div class="container">
    <div class="row">
            <form class="form-signin">
                <h1 class="h3 mb-3 font-weight-normal" style="text-align: center">Zaloguj się</h1>
                <input type="email" id="inputEmail" class="form-control" placeholder="E-mail" required autofocus="">
                <input type="password" id="inputPassword" class="form-control" placeholder="Hasło" required>
                
                <button class="btn btn-success btn-block" type="submit"><i class="fas fa-sign-in-alt"></i>Zaloguj się</button>
                <hr>
                <button class="btn btn-primary btn-block" type="button" id="btn-signup"></i>Załóż nowe konto</button>
                </form>
    
                
    </div>
</div>

@endsection