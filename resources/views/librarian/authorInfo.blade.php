@extends('layouts.layout-librarian')

@section('title', 'Autor '.$author->last_name." ".$author->first_names.' - informacje')

@section('content')

<div class="container">
    {{-- <div class="row">
        <div class="col-sm-12 "> <button type="button" onclick="goBack()" class="btn btn-sm btn-secondary btn-rounded"><i class="fas fa-arrow-left"></i> Powrót do wyszukiwania</button>
        </div>
    </div> --}}
        <div class="card border-danger my-1 ">
            <div class="card-body ">
                <h5 class="card-title">Szczegóły o autorze</h5>
                <div class=card-text">
                    <ul class="list-unstyled">
                        <li><strong>Nazwisko i imiona: </strong>{{$author->last_name}}, {{$author->first_names}}</li>
                    <li><strong>Książki: </strong> {{$author->books->count()}}
                            <ul class="list-group mt-2 mx-lg-5">
                            @foreach ($author->books as $book)
                            <li class="list-group-item"><a href="/pracownik/ksiazki/{{$book->id}}" class="a-link-navy">{{$book->title}}</a>
                            </li>
                            @endforeach

                        </ul>
                        </li>

                    </ul>
                </div>
        </div>
    </div>
</div>

@endsection