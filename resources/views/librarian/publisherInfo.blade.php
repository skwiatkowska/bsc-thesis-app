@extends('layouts.layout-librarian')

@section('title', 'Wydawnictwo '.$publisher->name)

@section('content')

<div class="container">
    {{-- <div class="row">
        <div class="col-sm-12 "> <button type="button" onclick="goBack()" class="btn btn-sm btn-secondary btn-rounded"><i class="fas fa-arrow-left"></i> Powrót do wyszukiwania</button>
        </div>
    </div> --}}
    <div class="card border-danger my-1">
        <div class="card-body ">
            <h5 class="card-title">Szczegóły o wydawnictwie</h5>
            <div class=card-text ">
                <ul class="list-unstyled">
                    <li><strong>Nazwa: </strong>{{$publisher->name}}</li>
                    <li><strong>Książki: </strong> {{$publisher->books->count()}}

                        <ul class="list-group mt-2 mx-lg-5">
                            @foreach ($publisher->books as $book)
                            <li class="list-group-item"><a href="/pracownik/ksiazki/{{$book->id}}"
                                    class="a-link-navy"><strong>{{$book->title}}</strong></a> - @foreach ($book->authors as $author)
                                    <a href="/pracownik/autorzy/{{$author->id}}"
                                        class="a-link-navy">{{$author->last_name}}, {{$author->first_names}}</a>
                                    {{ $loop->last ? '' : ' •' }}
                                    @endforeach
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