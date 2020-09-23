@extends('layouts.layout-librarian')

@section('title', 'Książka '.$book->title.' - informacje')

@section('content')

<div class="container col-lg-10">
    {{-- <div class="row">
        <div class="col-sm-12 "> <button type="button" onclick="goBack()" class="btn btn-sm btn-secondary btn-rounded"><i class="fas fa-arrow-left"></i> Powrót do wyszukiwania</button>
        </div>
    </div> --}}
    <div class="card my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Szczegóły książki
                <div class="ml-auto">
                <a href="{{$book->id}}/edycja" class="px-2" title="Edytuj"><i class="fa fa-pencil-alt"></i></a>
                    <a href="#" title="Usuń"><i class="fa fa-trash-alt"></i></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="card-text">
                <ul class="list-unstyled">
                    <li><strong>Tytuł: </strong>{{$book->title}}</li>
                    <li><strong>ISBN: </strong>{{$book->isbn}}</li>
                    <li><strong>Autorzy: </strong>
                        @foreach ($book->authors as $author)
                        <a href="/pracownik/autorzy/{{$author->id}}" class="a-link-navy">{{$author->last_name}},
                            {{$author->first_names}}</a>
                        {{ $loop->last ? '' : ' •' }}
                        @endforeach
                    </li>
                    <li><strong>Wydawnictwo: </strong><a href="/pracownik/wydawnictwa/{{$publisher->id}}"
                            class="a-link-navy">{{$publisher->name}}</a>
                    </li>
                    <li><strong>Rok wydania: </strong>{{$book->publication_year}}</li>
                    <li><strong>Kategorie: </strong>
                        @foreach ($book->categories as $category)
                        {{$category->name}}
                        {{ $loop->last ? '' : ' •' }}
                        @endforeach
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Egzemplarze
                <div class="ml-auto">
                    <a href="#" class="px-2" title="Edytuj"><i class="fa fa-pencil-alt"></i></a>
                    <a href="#" title="Usuń"><i class="fa fa-trash-alt"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body ">
            <div class=card-text">

            </div>
        </div>
    </div>


</div>

<script>
    function goBack() {
  window.history.back();
}
</script>
@endsection