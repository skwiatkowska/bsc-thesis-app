@extends('layouts.layout-librarian')

@section('title', 'Użytkownicy - wyszukiwanie')

@section('content')

<div class="container ">
    <div class="row text-right mb-3">
        <div class="col-sm-12 "> <button type="button" class="btn btn-secondary btn-rounded"><a
                    href="/pracownik/czytelnicy/nowy">Nowy Czytelnik</a></button>
        </div>
    </div>
    <div class="row">
        <form class="form-inline col-12 justify-content-center" action="/pracownik/czytelnicy/znajdz" method="POST">
            {{ csrf_field() }}

            <div class="input-group mb-2 col-sm-12 col-lg-4 px-1">
                <div class="input-group-prepend">
                    <div class="input-group-text">Szukaj w:</div>
                </div>
                <select class="form-control search-in-select" name="searchIn">
                    <option value="pesel">PESEL</option>
                    <option value="lname">Nazwisko</option>
                </select>
            </div>
            <div class="input-group mb-2 col-sm-12 col-lg-6 px-1">
                <div class="input-group-prepend">
                    <div class="input-group-text">Fraza:</div>
                </div>
                <input type="text" class="form-control search-phrase" name="phrase" id="search-phrase-input">
                <button type="submit" id="find-book-submit-btn" class="btn btn-primary ml-4 px-lg-4">Szukaj</button>

            </div>

        </form>
    </div>
    @if (!empty($phrase))
    <div class="row mt-4">
        <p class="h6 text-center searchingInfo mx-auto">Aktualne wyszukiwanie: <strong>{{$phrase}}</strong></p>
    </div>

    @if (!empty($users))
    <div class="row mt-4">
        <div class="col-10 mx-auto">
            <table id="dynatable2" class="table table-striped table-bordered mt-1">
                <thead>
                    <tr>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>PESEL</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>PESEL</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>
                            <a href="/pracownik/czytelnicy/{{$user->id}}" target="_blank"><strong
                                    class="a-link-navy">{{$user->first_name}}</strong></a>
                        </td>
                        <td>
                            <a href="/pracownik/czytelnicy/{{$user->id}}" target="_blank"><strong
                                    class="a-link-navy">{{$user->last_name}}</strong></a>
                        </td>
                        <td>
                            <a href="/pracownik/czytelnicy/{{$user->id}}" target="_blank"><strong
                                    class="a-link-navy">{{$user->pesel}}</strong></a>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <p class="h6 text-center py-5">Nie znaleziono</p>
    @endif
    @endif
</div>

<script>
    $('#dynatable2').dynatable();

    $(".dynatable-search").hide();
    $(".dynatable-per-page").hide();
    $("tfoot").hide();
    $(".dynatable-per-page-label").css( "margin-right", "8px" );
    $("th").css( "padding", "5px" );

    //submit search a book form
//     $("#find-book-submit-btn").click(function(e){
//       e.preventDefault();
//       var searchIn = $(".search-in-select option:selected").val();
    
//       var searchPhraseInput = $("input[name=phrase]").val();
//       var searchPhraseSelect = $("#choose-category-select option:selected").val();
//       var searchPhrase;
//     if(searchIn == "category"){
//         searchPhrase = searchPhraseSelect;
//     }
//     else{
//         searchPhrase = searchPhraseInput;
//     }
//       $.ajax({
//          type:'POST',
//          dataType : 'json',
//          url:'/pracownik/katalog',
//          data: {_token:"{{csrf_token()}}", searchIn: searchIn, searchPhrase:searchPhrase},
//          success:function(data){
//             location.reload();
//             alert(data.success);
//          },
//         //  error: function(data){
//         //     alert(data.responseJSON.error);
//         //   }
//     });

//   });


  




</script>
@endsection