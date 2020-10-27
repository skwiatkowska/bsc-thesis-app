@extends('layouts.admin')

@section('title', 'Pracownik - strona startowa')

@section('content')

<div class="container">
    <h4 class="text-center">Stan ogólny na {{date('Y-m-d')}}
    </h4>
    <div class="row mt-3 mb-5">

        <div class="col-md-6 col-lg-3">
            <div class="card-counter red">
                <span class="count-name">Czytelnicy</span>
                <span class="count-numbers">{{$users->count()}}</span>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card-counter red">
                <span class="count-name">Wypożyczenia</span>
                <span class="count-numbers">{{$borrowings->count()}}</span>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card-counter red">
                <span class="count-name">Rezerwacje</span>
                <span class="count-numbers">{{$reservations->count()}}</span>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card-counter red">
                <span class="count-name">Egzemplarze</span>
                <span class="count-numbers">{{$bookItems->count()}}</span>
            </div>
        </div>

    </div>
    <h5 class="text-center mt-5">W tym tygodniu
    </h5>
    <div class="row my-3">

        <div class="col-md-6 col-lg-3">
            <div class="card-counter gray">
                <span class="count-name">Nowi Czytelnicy</span>
                <span class="count-numbers">{{$users->count()}}</span>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card-counter gray">
                <span class="count-name">Nowe wypożyczenia</span>
                <span class="count-numbers">{{$borrowings->count()}}</span>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card-counter gray">
                <span class="count-name">Nowe rezerwacje</span>
                <span class="count-numbers">{{$reservations->count()}}</span>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card-counter gray">
                <span class="count-name">Nowe egzemplarze</span>
                <span class="count-numbers">{{$bookItems->count()}}</span>
            </div>
        </div>

    </div>
</div>


@endsection