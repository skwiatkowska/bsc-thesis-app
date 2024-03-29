@extends('layouts.user')

@section('title', 'e-Katalog')

@section('content')

<div class="container my-5 form-card" style="min-height: 300px;">
    <div class="row pt-5">
        <form class="form-inline col-12 justify-content-center" action="/katalog" method="GET">
            <div class="input-group mb-2 col-sm-12 col-lg-4 px-1">
                <div class="input-group-prepend">
                    <div class="input-group-text">Szukaj w:</div>
                </div>
                <select class="form-control search-in-select" name="searchIn">
                    <option value="title">Tytuł</option>
                    <option value="author">Autor (nazwiska)</option>
                    <option value="isbn">ISBN</option>
                    <option value="publisher">Wydawnictwo</option>
                    <option value="category">Kategoria</option>

                </select>
            </div>
            <div class="input-group mb-2 col-sm-12 col-lg-6 px-1">
                <div class="input-group-prepend">
                    <div class="input-group-text">Fraza:</div>
                </div>
                <input type="text" class="form-control search-phrase" name="phrase" id="search-phrase-input">
                <select class="form-control search-phrase" name="searchPhrase" id="choose-category-select">
                    <option value="" selected disabled>Nie wybrano</option>

                    @foreach ($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
                <button type="submit" id="find-book-submit-btn" class="btn btn-primary ml-4 px-lg-4">Szukaj</button>

            </div>

        </form>
    </div>
    @if (!empty($phrase))
    <div class="row mt-4">
        <p class="h6 text-center searchingInfo mx-auto">Aktualne wyszukiwanie: <strong>{{$phrase}}</strong></p>
    </div>
    @endif
    @if ($books->count() > 0)
    <div class="row mt-5">
        <div class="col-10 mx-auto">
            <table id="dynatable" class="table table-striped table-bordered mt-1">
                <thead>
                    <tr>
                        <th style="width: 30%">Tytuł</th>
                        <th style="width: 35%">Autorzy</th>
                        <th style="width: 15%">Wydawnictwo</th>
                        <th style="width: 10%">ISBN</th>
                        <th style="width: 10%">Egzemplarze</th>
                        <th></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Tytuł</th>
                        <th>Autorzy</th>
                        <th>Wydawnictwo</th>
                        <th>ISBN</th>
                        <th>Egzemplarze</th>
                        <th></th>
                    </tr>
                </tfoot>

                <tbody>
                    @foreach ($books as $index => $book)
                    <tr>
                        <td>
                            <a href="/ksiazki/{{$book->id}}"><strong class="a-link-navy">{{$book->title}}</strong></a>
                        </td>
                        <td>
                            @foreach ($book->authors as $author)
                            <a href="/autorzy/{{$author->id}}" class="a-link-navy">{{$author->last_name}},
                                {{$author->first_names}}</a>
                                {{ $loop->last ? '' : ' •' }}

                            @endforeach
                        </td>
                        <td><a href="/wydawnictwa/{{$book->publisher->id}}"
                                class="a-link-navy">{{$book->publisher->name}}</a>
                        </td>
                        <td>{{$book->isbn}}</td>
                        <td>
                            {{ count($book->bookItems->where("status", "Dostępne"))}}/{{$book->bookItems->count()}}
                        </td>
                        <td>
                            <a href="/ksiazki/{{$book->id}}" type="button" class="btn btn-sm btn-primary">Wybierz</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    @if (!empty($phrase))
    <p class="h6 text-center py-5">Nie znaleziono</p>
    @endif
    @endif
</div>


@endsection
@section('script')
<script>
    
    $('#dynatable').dynatable();

    $(".dynatable-search").hide();
    $("tfoot").hide();
    $(".dynatable-per-page-label").css( "margin-right", "8px" );
    $("th").css( "padding", "5px" );

    $("#choose-category-select").hide();

    $(".search-in-select").change(function() {
        var val = $(this).find("option:selected").attr("value");
        if (val == 'category') {
            $("#search-phrase-input").hide();
            $("#choose-category-select").show();

        } else {
            $("#search-phrase-input").show();
            $("#choose-category-select").hide();
        }
    });

 
</script>
@endsection