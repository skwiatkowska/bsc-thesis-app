@extends('layouts.user')

@section('title', 'Autor '.$author->last_name." ".$author->first_names.' - informacje')


@section('content')

<div class="container col-lg-8 my-5">
    <div class="card my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Informacje o autorze
            </div>
        </div>

        <div class="card-body">
            <div class=card-text">
                <ul class="list-unstyled">
                    <li><strong>Nazwisko: </strong>{{$author->last_name}}</li>
                    <li><strong>Imiona: </strong>{{$author->first_names}}</li>
                    <li><strong>Książki: </strong> {{$author->books->count()}}
                        <ul class="list-group mt-2">
                            @foreach ($author->books as $book)
                            <li class="list-group-item"><a href="/ksiazki/{{$book->id}}"
                                    class="a-link-navy">{{$book->title}}</a>
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
