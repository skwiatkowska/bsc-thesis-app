@extends('layouts.layout-librarian')

@section('title', 'Wydawnictwa')

@section('content')

<div class="container col-lg-10 offset-lg-1">
  <div class="row justify-content-center">
    <div class="col-md-8">
        <div class="input-group col-12 px-0">
          <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fas fa-search" aria-hidden="true"></i>
            </span>
          </div>

          <input class="form-control my-0 py-1 listSearch"  type="text" placeholder="Znajdź wydawnictwo..."
            aria-label="Search" name="name" required>

    </div>
    <br>

    <ul class="list-group sorted-list item-list">
      @if($publishers->isEmpty())

      <p class="h6 text-center py-5 emptyDBInfo">Baza danych jest pusta. </p>
      @else


      @foreach ($publishers as $publisher)
      <li class="list-group-item">{{ $publisher->name }}</li>
      @endforeach
      @endif

    </ul>
  </div>
</div>


<script>
  $(document).ready(function(){
      $( ".item-list" ).append('<p class="h6 text-center py-5 noSuchInfo">Nie znaleziono. Dodaj książkę z takim wydawnictwem <a class="a-link" href="/pracownik/ksiazki/nowa">tutaj</a></p>');        
      
      $(".noSuchInfo").hide();
  

      $(".listSearch").on("keyup", function() {
        $(".emptyDBInfo").hide();
          var value = $(this).val().toLowerCase();
          
          $(".item-list li").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
         
          var numOfVisibleRows = $(".item-list li:visible").length;
           
          if(numOfVisibleRows == 0){
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