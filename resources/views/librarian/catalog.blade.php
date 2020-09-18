@extends('layouts.layout-librarian')

@section('title', 'e-Katalog')

@section('content')

<div class="container ">
    <div class="row text-right mb-3">
        <div class="col-sm-12 "> <button type="button" class="btn btn-secondary btn-rounded">Nowa książka</button>
        </div>
    </div>
    <div class="row justify-content-center">
        <form class="form-inline">
            <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">Szukaj w:</div>
                </div>
                <select class="form-control search-in-select">
                    <option value="all">Wszystkie pola</option>
                    <option value="title">Tytuł</option>
                    <option value="author">Autor</option>
                    <option value="publisher">Wydawnictwo</option>
                    <option value="category">Kategoria</option>

                </select>
            </div>
            <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">Szukana fraza:</div>
                </div>
                <input type="text" class="form-control" id="search-phrase-input">
                <select class="form-control" id="choose-category-select">
                    @foreach ($categories as $category)

                    <option value="{{$category->id}}">{{$category->name}}</option>
                   
                    @endforeach
                </select>
            </div>



            <button type="submit" class="btn btn-primary mb-2">Szukaj</button>
        </form>
    </div>

</div>

<script>
    $(document).ready(function() {
    $("#choose-category-select").hide();

    $(".search-in-select").change(function() {
        var val = $(this).find("option:selected").attr("value");
        console.log("val" + val);
        if (val == 'category') {
            $("#search-phrase-input").hide();
            $("#choose-category-select").show();

        } else {
            $("#search-phrase-input").show();
            $("#choose-category-select").hide();
        }
    })
});


  </script>

<script>
 
    
  


</script>
@endsection

