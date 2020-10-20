@extends('layouts.admin')

@section('title', 'Wypożyczanie: '.$item->book->title.', egzemplarz: '.$item->bookitem_id)

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-1"> <button type="button" onclick="goBack()" class="btn btn-sm btn-secondary btn-rounded"><i
                    class="fa fa-arrow-left"></i> Powrót</button>
        </div>


        <div class="progressbar-wrapper mb-5 col-sm-11 ml-0">
            <ul class="progressbar">
                <li class="active">Wybór egzemplarza</li>
                <li class="active">Wybór czytelnika</li>
                <li>Gotowe!</li>
            </ul>
        </div>
    </div>
    {{-- {{$item}} --}}
    <div class="row">
        <form class="form-inline col-12 justify-content-center" action="/pracownik/czytelnicy/znajdz" method="POST">
            {{ csrf_field() }}
            <div class="input-group mb-2 col-sm-12 col-lg-3 px-1">
                <div class="input-group-prepend">
                    <div class="input-group-text">Szukaj w:</div>
                </div>
                <select class="form-control search-in-select" name="searchIn">
                    <option value="pesel">PESEL</option>
                    <option value="lname">Nazwisko</option>
                </select>
            </div>
            <div class="input-group col-sm-12 col-lg-4 mb-2 px-1">
                <div class="input-group-prepend">
                    <div class="input-group-text">Fraza:</div>
                </div>
                <input type="text" class="form-control search-phrase" name="phrase" id="search-phrase-input">

            </div>
            <div class="input-group col-lg-2 mb-2">
                <button type="submit" id="find-book-submit-btn" class="btn btn-primary ml-4 px-lg-4">Znajdź Czytelnika</button>

            </div>
            <div class="input-group mb-2 col-lg-2 ml-auto">
                <button type="button" class="btn btn-secondary btn-rounded"><a
                href="/pracownik/czytelnicy/nowy">Nowy Czytelnik</a></button>
    </div>

        </form>
    </div>
</div>

@endsection