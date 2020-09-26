@extends('layouts.layout-librarian')

@section('title', 'Kategorie')

@section('content')

<div class="container col-lg-10 offset-lg-1">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form name="newCategoryForm">
        {{-- {{ csrf_field() }} --}}
        <div class="input-group col-12 px-0">
          <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fas fa-search" aria-hidden="true"></i>
            </span>
          </div>

          <input class="form-control my-0 py-1 listSearch" type="text" placeholder="Znajdź lub dodaj kategorię..."
            aria-label="Search" name="name" required>
          <div class="input-group-prepend">
            <button type="submit" class="btn btn-primary btn-submit">
              <i class="fas fa-plus"></i>
            </button></div>
      </form>
    </div>

    <br>

    <ul class="list-group sorted-list item-list">
      @if($categories->isEmpty())
      <p class="h6 text-center py-5 emptyDBInfo">Brak kategorii. Wpisz nową nazwę powyżej, kliknij <i class="fas fa-plus"></i> i
        dodaj nową</p>
      @else


      @foreach ($categories as $category)
      <li class="list-group-item">{{ $category->name }}</li>
      @endforeach
      @endif

    </ul>
  </div>
</div>

<script>
  //form submitting
  $(".btn-submit").click(function(e){
      e.preventDefault();
      var name = $("input[name=name]").val();
      if(!name.length){
        alert("Podaj nazwę kategorii");
        return false;
      }
      $.ajax({
         type:'POST',
         dataType : 'json',
         url:'/pracownik/kategorie',
         data: {_token:"{{csrf_token()}}", name: name},
         success:function(data){
            location.reload();
            alert(data.success);
         },
         error: function(data){
            alert(data.responseJSON.error);
          }
}     );

  });


  $(document).ready(function(){
      $( ".item-list" ).append('<p class="h6 text-center py-5 noSuchInfo">Nie ma takiej kategorii. Kliknij <i class="fas fa-plus"></i> i dodaj nową</p>');           
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