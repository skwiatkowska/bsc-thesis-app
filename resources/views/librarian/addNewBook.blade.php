@extends('layouts.layout')

@section('title', 'Add new book')

@section('content')

<div class="container">
    <form action="/add-book" method="POST">
        @csrf
        <input type="text" name="title">
        <input type="text" name="author">
        <input type="submit" value="Add">
    </form>
</div>

@endsection