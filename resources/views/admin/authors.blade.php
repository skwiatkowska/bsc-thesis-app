@extends('layouts.admin')

@section('title', 'Autorzy')

@section('content')

<div class="container col-lg-10 offset-lg-1">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="input-group col-12 px-0">
        <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fas fa-search" aria-hidden="true"></i>
          </span>
        </div>

        <input class="form-control my-0 py-1 tableSearch" type="text" placeholder="Znajdź autora..." aria-label="Search"
          name="name" required>

      </div>

      <br>

      @if($authors->isEmpty())

      <p class="h6 text-center py-5 emptyDBInfo">Baza danych jest pusta. </p>
      @else

      <table class="table table-bordered text-center">
        <thead>
          <tr>
            <th>Nazwisko</th>
            <th>Imię / Imiona</th>
            <th></th>
          </tr>
        </thead>
        <tbody class="item-table">
          @foreach ($authors as $author)
          <tr>
            <td>{{$author->last_name}}
            </td>
            <td>{{$author->first_names}}
            </td>
            <td><a href="/pracownik/autorzy/{{$author->id}}" type="button"
              class="btn btn-primary btn-rounded">
              Profil
          </a>
      </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      @endif

    </div>
  </div>

  <script>
    $(document).ready(function(){
      $( ".col-md-8" ).append('<p class="h6 text-center py-5 noSuchInfo">Nie znaleziono. Dodaj książkę z takim autorem <a class="a-link" href="/pracownik/ksiazki/nowa">tutaj</a></p>');           
      $(".noSuchInfo").hide();
  

      $(".tableSearch").on("keyup", function() {
        $(".emptyDBInfo").hide();
        
          var value = $(this).val().toLowerCase();
          
          $(".item-table tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
         
          var numOfVisibleRows = $(".item-table tr:visible").length;
           
          if(numOfVisibleRows == 0){
            $("table").hide();
            $(".noSuchInfo").show();
          }
          if(value.length == 0){
              $(".noSuchInfo").hide();
            }
     
      })

      })
  });
  </script>

  @endsection