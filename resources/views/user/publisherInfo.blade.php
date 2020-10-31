@extends('layouts.user')

@section('title', 'Wydawnictwo '.$publisher->name.' - informacje')


@section('content')

<div class="container col-lg-8 my-5">
    <div class="card my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Informacje o wydawnictwie
            </div>
        </div>

        <div class="card-body">
            <div class=card-text">
                <ul class="list-unstyled">
                    <li><strong>Nazwa: </strong>{{$publisher->name}}
                    </li>
                    <br>
                    <li><strong>Książki: </strong> {{$publisher->books->count()}}
                        <ul class="list-group mt-2">
                            @foreach ($publisher->books as $book)
                            <li class="list-group-item"><a href="/pracownik/ksiazki/{{$book->id}}"
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
