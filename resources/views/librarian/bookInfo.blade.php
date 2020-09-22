@extends('layouts.layout-librarian')

@section('title', 'Książka '.$book->title.' - informacje')

@section('content')

<div class="container">
    {{-- <div class="row">
        <div class="col-sm-12 "> <button type="button" onclick="goBack()" class="btn btn-sm btn-secondary btn-rounded"><i class="fas fa-arrow-left"></i> Powrót do wyszukiwania</button>
        </div>
    </div> --}}
    <div class="card border-danger my-1">
        <div class="card-body ">
            <h5 class="card-title">Szczegóły książki</h5>
            <div class=card-text">
                <ul class="list-unstyled">
                    <li><strong>Tytuł: </strong>{{$book->title}}</li>
                    <li><strong>ISBN: </strong>{{$book->isbn}}</li>
                    <li><strong>Autorzy: </strong>
                        @foreach ($book->authors as $author)
                        <a href="/pracownik/autorzy/{{$author->id}}" 
                            class="a-link-navy">{{$author->last_name}}, {{$author->first_names}}</a>
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
                    <br>
                    <li><strong>Egzemplarze: </strong></li>

                </ul>
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