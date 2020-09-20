@extends('layouts.layout-librarian')

@section('title', 'Nowa książka')

@section('content')

<div class="cotainer">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mt-0">
                <div class="card-header">Nowa książka</div>
                <div class="card-body">
                    <form name="newBookForm" action="/pracownik/ksiazki/nowa" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group row required">
                            <label for="title"
                                class="col-md-4 col-form-label  control-label text-md-right">Tytuł</label>
                            <div class="col-md-6">
                                <input type="text" id="title" class="form-control" name="title" required>
                            </div>
                        </div>

                        <div class="form-group row required">
                            <label for="title"
                                class="col-md-4 col-form-label  control-label text-md-right">ISBN</label>
                            <div class="col-md-6">
                                <input type="text" id="isbn" class="form-control" name="isbn" required>
                            </div>
                        </div>

                        <div class="form-group row required">
                            <label for="author"
                                class="col-md-4 col-form-label control-label text-md-right">Autor</label>
                            <div class="col-md-6">
                                <div class="control-group form-group mb-0">

                                    <div class="input-group col-xs-3">
                                        <select data-live-search="true" id="authors" name="authors[]"
                                            class="form-control">
                                            <option value="" selected disabled>Wybierz</option>
                                            @foreach ($authors as $author)
                                            <option value="{{$author->id}}">{{$author->last_name}},
                                                {{$author->first_names}}</option>
                                            @endforeach


                                        </select>

                                    </div>


                                </div>
                                <!-- DYNAMIC ELEMENT TO CLONE -->
                                <div class="control-group form-group mt-1 mb-2 dynamic-element" style="display:none">
                                    <div class="input-group col-xs-3">
                                        <select data-live-search="true" id="authors" name="authors[]"
                                            class="form-control">
                                            <option value="" selected disabled>Wybierz</option>
                                            @foreach ($authors as $author)
                                            <option value="{{$author->id}}">{{$author->last_name}},
                                                {{$author->first_names}}</option>
                                            @endforeach

                                        </select>
                                            <button id="b1" class="btn btn-danger delete ml-2"
                                                type="button">X</button>
                                    </div>


                                </div>
                                <!-- END OF DYNAMIC ELEMENT -->

                                <fieldset class="pb-0 mb-0 mt-1">
                                    <div class="dynamic-stuff">
                                    </div>
                                    <div class="input-group col-xs-3">
                                        <button type="button" class="btn btn-light mr-auto mb-2"
                                            data-toggle="modal" data-target="#newAuthorModal">
                                            <i class="fas fa-plus"></i> nowy autor
                                        </button>
                                        <button type="button"
                                            class="btn add-one btn-danger mb-2 ml-sm-auto ml-md-0 ml-lg-auto"
                                            data-toggle="modal">
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
                                        <button type="button" class="btn btn-sm btn-light  ml-2 py-0" data-toggle="modal"
                                        data-target="#newPublisherModal">
                                        <i class="fas fa-plus"></i> nowe wydawnictwo

                                    </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row  mt-2 mb-0">
                            <label for="year" class="col-md-4 col-form-label control-label text-md-right">Rok
                                wydania</label>
                            <div class="col-md-6 col-lg-2 ">
                                <select id="year" name="year" class="form-control py-1">
                                    <option value="" selected disabled>Wybierz</option>
                                </select>
                            </div>
                            <label for="numverOfItems"
                                class="col-md-4 col-lg-2 mt-md-2 mt-lg-0 mx-md-0 col-form-label control-label text-md-right">Egzemplarze
                            </label>
                            <div class="col-md-6 col-lg-2 mt-md-2 mt-lg-0">
                                <input type="number" id="numverOfItems" class="form-control" name="numberOfItems"
                                    required>
                            </div>
                        </div>

                        <div class="form-group required row mb-1">
                            <label for="category"
                                class="col-md-4 col-form-label control-label text-md-right">Kategorie</label>
                            <div class="col-md-6 sorted">
                                @if($categories->isEmpty())

                                <p class="mt-1">Brak kategorii. <a class="a-link" href="/pracownik/kategorie">Kliknij i
                                        dodaj nową</a></p>
                                @else
                                @foreach ($categories as $category)
                                <span class="button-checkbox m-1">
                                    <button type="button" class="btn btn-sm category-btn"
                                        data-color="secondary">{{$category->name}}</button>
                                    <input type="checkbox" id="{{$category->id}}" value="{{$category->id}}"
                                        name="categories[]" class="input-hidden" />
                                </span>
                                @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="row d-flex justify-content-center">

                            <button type="submit" class="btn btn-lg btn-primary" id="confirm-btn">
                                Dodaj
                            </button>
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
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Nazwa</label>
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
                    <div class="form-group row">
                        <label for="fname" class="col-md-4 col-form-label text-md-right">Imiona</label>
                        <div class="col-md-6">
                            <input type="text" id="fname" class="form-control" name="fname" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="lname" class="col-md-4 col-form-label text-md-right">Nazwisko</label>
                        <div class="col-md-6">
                            <input type="text" id="lname" class="form-control" name="lname" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    <button type="submit" id="new-author-btn-submit" class="btn btn-primary">Dodaj</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
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
  

    // dynamic input for authors
    // source: https://codepen.io/llooll/pen/eVMvGR

    //Clone the hidden element and shows it
    $('.add-one').click(function(){
    $('.dynamic-element').first().clone().appendTo('.dynamic-stuff').show();
    attach_delete();
    });


    function attach_delete(){
    $('.delete').off();
    $('.delete').click(function(){
        console.log("click");
        $(this).closest('.form-group').remove();
    });
    }


    if($(".button-checkbox").length == 0){
        $("#confirm-btn").attr('disabled', true);
    }

  

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
</script>
@endsection