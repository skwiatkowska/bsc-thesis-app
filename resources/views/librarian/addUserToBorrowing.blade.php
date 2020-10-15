@extends('layouts.admin')

@section('title', 'Wypożyczanie: '.$item->book->title)

@section('content')

<div class="container">
    {{$item}}
 </div>

@endsection

