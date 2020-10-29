@extends('layouts.user')

@section('title', 'Potwierdź rezerwację')

@section('content')
{{-- NIEUZYWANE --}}
<div class="container-fluid">
    <div class="row justify-content-center my-4">
        <div class="col-md-6">
            <div class="card my-3 form-card">
                <div class="card-header">Potwierdź rezerwację</div>
                <div class="card-body mt-0">
                    <form action="/logowanie" method="POST">
                        {{ csrf_field() }}

                        <div class=" col-md-8 mx-auto">
                            <table class="table text-center">
                                <tbody>
                                    <tr>
                                        <td style="width:50%">
                                            Tytuł:</td>
                                        <td>{{$book->title}}</td>
                                    </tr>
                                    <tr>
                                        <td>Autorzy:</td>
                                        <td>@foreach ($book->authors as $author)
                                            {{$author->last_name}}, {{$author->first_names}}
                                            {{ $loop->last ? '' : ' •' }}
                                            @endforeach</td>
                                    </tr>
                                    <tr>
                                        <td>Egzemplarz:</td>
                                        <td>{{$item->book_item_id}}</td>
                                    </tr>
                                    <tr>
                                        <td>Wydawnictwo:</td>
                                        <td>{{$book->publisher->name}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row justify-content-center">
                            <button type="submit" class="btn btn-primary mr-3">
                                Wypożycz
                            </button>
                            <button type="button" class="btn btn-secondary">Anuluj</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection