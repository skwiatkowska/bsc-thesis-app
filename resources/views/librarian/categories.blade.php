@extends('layouts.layout-librarian')

@section('title', 'Kategorie')

@section('content')

<div class="container col-lg-10 offset-lg-1">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form class="form-inline" method="POST" action="/pracownik/kategorie" name="newCategoryForm">
        {{ csrf_field() }}
      <div class="input-group col-12 px-0">
        <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fas fa-search" aria-hidden="true"></i>
          </span>
        </div>
        
        <input class="form-control my-0 py-1" id="categoryListSearch" type="text" placeholder="Znajdź kategorię..."
          aria-label="Search" name="name" required>
        <div class="input-group-prepend">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i>
          </button>
        </form>
        </div>
      </div>
      <br>

      <ul class="list-group" id="categoryList">
        @if($categories->isEmpty())

        <p class="h6 text-center py-5" id="emptyCategoriesInfo">Brak kategorii. Kliknij <i class="fas fa-plus"></i> i dodaj nową</p>         
        @else
            
      
        @foreach ($categories as $category)
          <li class="list-group-item">{{ $category->name }}</li>
        @endforeach
        @endif

      </ul>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
      $( "#categoryList" ).append('<p class="h6 text-center py-5" id="noSuchCategoryInfo">Nie ma takiej kategorii. Kliknij <i class="fas fa-plus"></i> i dodaj nową</p>');           
      $("#noSuchCategoryInfo").hide();
  

      $("#categoryListSearch").on("keyup", function() {
        $("#emptyCategoriesInfo").hide();
        
          //alert($("#noSuchCategoryInfo").length)
          var value = $(this).val().toLowerCase();
          
          $("#categoryList li").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
         
          var numOfVisibleRows = $("#categoryList li:visible").length;
           
          if(numOfVisibleRows == 0){
            $("#noSuchCategoryInfo").show();
          }
          if(value.length == 0){
              $("#noSuchCategoryInfo").hide();
            }
     
      })

      })
  });
</script>
{{-- <!-- Modal -->
<div class="modal fade" id="newCategoryModal" tabindex="-1" role="dialog" aria-labelledby="newCategoryModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="/pracownik/kategorie" name="newCategoryForm">
        {{ csrf_field() }}
        <div class="modal-header">
          <h5 class="modal-title" id="newCategoryModalLabel">Nowa kategoria</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-right">Nazwa</label>
            <div class="col-md-6">
              <input type="text" id="name" class="form-control" name="name" required>
            </div>
          </div>
        </div>
        <div class="modal-footer p-3">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
          <button type="submit" class="btn btn-primary">Zapisz</button>
        </div>
      </form>
    </div>
  </div>
</div> --}}


@endsection