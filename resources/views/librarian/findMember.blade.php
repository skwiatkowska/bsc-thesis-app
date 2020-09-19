@extends('layouts.layout-librarian')

@section('title', 'Znajdź Czytelnika')

@section('content')

<div class="container ">
    <div class="row ">
        <form class="form-inline col-12 justify-content-center" method="POST" action="/pracownik/szukaj">
            <div class="input-group mb-2 col-sm-12 col-lg-3 px-1">
                <div class="input-group-prepend">
                    <div class="input-group-text">Szukaj w:</div>
                </div>
                <select class="form-control search-in-select">
                    <option value="pesel">PESEL</option>
                    <option value="last-name">Nazwisko</option>

                </select>
            </div>
            <div class="input-group mb-2 col-sm-12 col-lg-6 px-1">
                <div class="input-group-prepend">
                    <div class="input-group-text">Fraza:</div>
                </div>
                <input type="text" class="form-control" name="phrase" id="search-phrase-input">
                
                <button type="submit" class="btn btn-primary ml-4 px-lg-4">Szukaj</button>

            </div>
           
        </form>
    </div>

</div>
@endsection