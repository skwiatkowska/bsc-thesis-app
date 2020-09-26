@extends('layouts.layout-librarian')

@section('title', 'Książka '.$book->title.' - edycja')

@section('content')

<div class="cotainer">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mt-0">
                <div class="card-header">Edycja książki</div>
                <div class="card-body">
                    <form name="editBookForm" action="/pracownik/ksiazki/{{$book->id}}/edycja" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group row required">
                            <label for="title"
                                class="col-md-4 col-form-label  control-label text-md-right">Tytuł</label>
                            <div class="col-md-6">
                                <input type="text" id="title" class="form-control" name="title" value="{{$book->title}}"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row required">
                            <label for="title" class="col-md-4 col-form-label  control-label text-md-right">ISBN</label>
                            <div class="col-md-6">
                                <input type="text" id="isbn" class="form-control" name="isbn" value="{{$book->isbn}}"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row required">
                            <label for="author"
                                class="col-md-4 col-form-label control-label text-md-right">Autor</label>
                            <div class="col-md-6">

                                <div class="control-group form-group mb-0">
                                    <div class="input-group col-xs-3 field_wrapper">
                                        <select data-live-search="true" id="authors" name="authors[]"
                                            class="form-control select-author">
                                            <option value="" selected disabled>Wybierz</option>
                                            @foreach ($authors as $author)
                                            <option value="{{$author->id}}">{{$author->last_name}},
                                                {{$author->first_names}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <fieldset class="pb-0 mb-0 mt-2">
                                    <div class="input-group col-xs-3">
                                        <button type="button" class="btn btn-light mr-auto mb-2" data-toggle="modal"
                                            data-target="#newAuthorModal">
                                            <i class="fas fa-plus"></i> nowy autor
                                        </button>
                                        <button type="button"
                                            class="btn add-one btn-danger add_button mb-2 ml-sm-auto ml-md-0 ml-lg-auto">
                                
                                            <i class="fas fa-plus"></i> kolejny autor

                                        </button>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="form-group row required">
                            <label for="publisher"
                                class="col-md-4 col-form-label control-label text-md-right">Wydawnictwo</label>
                            <div class="col-md-6">
                                <div class="control-group form-group mb-0">
                                    <div class="input-group col-xs-3">
                                        <select data-live-search="true" id="publisher" name="publisher"
                                            class="form-control">
                                            <option value="" selected disabled>Wybierz</option>
                                            @foreach ($publishers as $publisher)
                                            <option value="{{$publisher->id}}">{{$publisher->name}}</option>
                                            @endforeach


                                        </select>
                                        <button type="button" class="btn btn-sm btn-light  ml-2 py-0"
                                            data-toggle="modal" data-target="#newPublisherModal">
                                            <i class="fas fa-plus"></i> nowe wydawnictwo

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row required  mt-2 mb-0">
                            <label for="year" class="col-md-4 col-form-label control-label text-md-right">Rok
                                wydania</label>
                            <div class="col-md-6 col-lg-2 ">
                                <input id="year" name="year" value="{{$book->publication_year}}"
                                    class="form-control py-1 required">
                            </div>
                            <label for="numverOfItems"
                                class="col-md-4 col-lg-2 mt-md-2 mt-lg-0 mx-md-0 col-form-label control-label text-md-right">Egzemplarze
                            </label>
                            <div class="col-md-6 col-lg-2 mt-md-2 mt-lg-0">
                                <input id="numberOfItems" value="{{$book->book_items_number}}" class="form-control"
                                    name="numberOfItems" required>
                            </div>
                        </div>

                        <div class="form-group required row mb-1">
                            <label for="category"
                                class="col-md-4 col-form-label control-label text-md-right">Kategorie</label>
                            <div class="col-md-6 sorted">

                                @foreach ($categories as $category)
                                <span class="button-checkbox m-1">
                                    <button type="button" class="btn btn-sm category-btn"
                                        data-color="secondary">{{$category->name}}</button>
                                    <input type="checkbox" id="{{$category->id}}" value="{{$category->id}}"
                                        name="categories[]" class="input-hidden" />
                                </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="row d-flex justify-content-center mt-3">

                            <button type="submit" class="btn btn-primary mr-3" id="new-book-btn-submit">Edytuj</button>
                            <button class="btn btn-secondary">Anuluj</button>
                        </div>
                </div>


                </form>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="newPublisherModal" tabindex="-1" role="dialog" aria-labelledby="newPublisherModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="newPublisherForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="newPublisherModalLabel">Nowe wydawnictwo
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group required row">
                        <label for="name" class="col-md-4 col-form-label control-label text-md-right">Nazwa</label>
                        <div class="col-md-6">
                            <input type="text" id="name" class="form-control" name="name" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    <button type="submit" id="new-publisher-btn-submit" class="btn btn-primary">Dodaj</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="newAuthorModal" tabindex="-1" role="dialog" aria-labelledby="newAuthorModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="newAuthorForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="newAuthorModalLabel">Nowy autor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row required">
                        <label for="fname" class="col-md-4 col-form-label control-label text-md-right">Imiona</label>
                        <div class="col-md-6">
                            <input type="text" id="fname" class="form-control" name="fname" required>
                        </div>
                    </div>
                    <div class="form-group required row">
                        <label for="lname" class="col-md-4 col-form-label control-label text-md-right">Nazwisko</label>
                        <div class="col-md-6">
                            <input type="text" id="lname" class="form-control" name="lname" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary " data-dismiss="modal">Zamknij</button>
                    <button type="submit" id="new-author-btn-submit" class="btn btn-primary">Dodaj</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var categories = {!! json_encode($book->categories) !!};
    var publisher = {!! json_encode($book->publisher) !!};
    var authors = {!! json_encode($book->authors) !!};
    $("#publisher").val(publisher.id);
    console.log($("select#publisher").children('option:selected'));

    var i;
    var categoriesNames = [];
    for(i=0; i < categories.length; i++){
        categoriesNames.push(categories[i].name);      
    }

    $("#authors:first").val(authors[0].id);
    
        var maxField = 6; 
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        
        var fieldHTML = '<div class="input-group col-xs-3 mt-2 field_wrapper">'
        +'<select data-live-search="true" id="authors" name="authors[]" class="form-control select-author">'
        +'<option value="" selected disabled>Wybierz</option>'
        +'@foreach ($authors as $author)'
        +'<option value="{{$author->id}}">{{$author->last_name}},{{$author->first_names}}</option>'
        +'@endforeach'
        +'</select>'
        +'<a type="button" class=" remove_button ml-2 my-auto"><strong><i class="fas fa-trash-alt"></i></strong></a>';
       
        var x = 1; 
        
        $(addButton).click(function(){
            if(x < maxField){ 
                x++; 
                $(wrapper).append(fieldHTML); //Add field html
            }
        });
        
        $(wrapper).on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).parent('div').remove(); 
            x--; 
        });
        for(i=1; i < authors.length;i++){
        if(x < maxField){ 
                x++; 
                var fieldHTMLAuthor = '<div class="input-group col-xs-3 mt-2 field_wrapper">'
        +'<select data-live-search="true" id="authors" name="authors[]" class="form-control select-author">'
        +'<option value="'+authors[i].id+'" selected disabled>'+authors[i].last_name + ', ' + authors[i].first_names+'</option>'
        +'</select>'
        +'<a type="button" class=" remove_button ml-2 my-auto"><strong><i class="fas fa-trash-alt"></i></strong></a>';
       
                $(wrapper).append(fieldHTMLAuthor); //Add field html

        }

    }
    

    //submit new author form in modal
//     $("#new-author-btn-submit").click(function(e){
//       e.preventDefault();
//       var title = $("input[name=title]").val();
//       var isbn = $("input[name=isbn]").val();
     
//       $.ajax({
//          type:'POST',
//          dataType : 'json',
//          url:'/pracownik/autorzy',
//          data: {_token:"{{csrf_token()}}", title: title, isbn:isbn},
//          success:function(data){
//             location.reload();
//             alert(data.success);
//          },
//          error: function(data){
//             alert(data.responseJSON.error);
//           }
//     });

//   });



//submit new author form in modal
$("#new-author-btn-submit").click(function(e){
      e.preventDefault();
      var fname = $("input[name=fname]").val();
      var lname = $("input[name=lname]").val();
     
      $.ajax({
         type:'POST',
         dataType : 'json',
         url:'/pracownik/autorzy',
         data: {_token:"{{csrf_token()}}", fname: fname, lname:lname},
         success:function(data){
            location.reload();
            alert(data.success);
         },
         error: function(data){
            alert(data.responseJSON.error);
          }
    });

  });



//submit new publisher form in modal
$("#new-publisher-btn-submit").click(function(e){
      e.preventDefault();
      var name = $("input[name=name]").val();
     
      $.ajax({
         type:'POST',
         dataType : 'json',
         url:'/pracownik/wydawnictwa',
         data: {_token:"{{csrf_token()}}", name: name},
         success:function(data){
            location.reload();
            alert(data.success);
         },
         error: function(data){
            alert(data.responseJSON.error);
          }
    });
  });


    //publication year
    var html = '';
 for (var i = 1900; i <= (new Date).getFullYear(); i++) {
   html += '<option>' + i + '</option>';
 }
 $('#year').append(html);


    //select search init
    $('select').selectize({
          sortField: 'text'
      });
  
    
 

// categories checkboxes
$(function () {
    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
               
            };

            if(categoriesNames.includes($button.html())){
                $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
            }
            
        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');
            $button.data('state', (isChecked) ? "on" : "off");
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active')
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        function init() {
            updateDisplay();
        }
        init();
    });
});

// $(window).unload(saveSettings);
// 		loadSettings();
	

// 	function loadSettings() {
// 		$('#title').val(localStorage.title);
// 		$('#isbn').val(localStorage.isbn);
// 		$("#numberOfItems").val(localStorage.numberOfItems);
//         $("#publisher").val(localStorage.publisher);

// 	}

// 	function saveSettings() {
// 		localStorage.title = $('#title').val();
// 		localStorage.isbn = $('#isbn').val();
// 		localStorage.numberOfItems = $('#numberOfItems').val();
// 		localStorage.publisher = $("#publisher").val();
// 	}

</script>
@endsection