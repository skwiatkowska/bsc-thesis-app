@extends('layouts.layout')

@section('title', 'Kontakt')

@section('content')

<div class="container">
    <form action="pracownik/dodaj-ksiazke" method="POST">
        @csrf
        <input type="text" name="title">
        <input type="text" name="author">
        <input type="submit" value="Add">
    </form>
</div>

@endsection