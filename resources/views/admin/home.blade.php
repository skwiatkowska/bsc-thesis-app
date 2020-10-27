@extends('layouts.admin')

@section('title', 'Pracownik - strona startowa')

@section('content')

<div class="container">
    Wypożyczenia:
    {{$borrowings->count()}}
    <br>
    Rezerwacje:
    {{$reservations->count()}}
    <br>
    Czytelnicy:
    {{$users->count()}}
    <br>
    Książki:
    {{$books->count()}}
    <br>
    Egzemplarze:
    {{$bookItems->count()}}
</div>

@endsection