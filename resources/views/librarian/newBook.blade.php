@extends('layouts.layout-librarian')

@section('title', 'Nowa książka')

@section('content')

<div class="cotainer">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Nowa książka</div>
                <div class="card-body">
                    <form name="newBookForm" action="/pracownik/ksiazki/nowa" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">Tytuł</label>
                            <div class="col-md-6">
                                <input type="text" id="title" class="form-control" name="title" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="author" class="col-md-4 col-form-label text-md-right">Autor</label>
                            <div class="col-md-6">
                                <div class="control-group form-group mb-0">
                                    <label for="author" class="col-form-label ml-auto mr-auto"><small>Wybierz autora z bazy danych: </small></label>

                                    <div class="input-group col-xs-3">
                                        <select data-live-search="true" id="authors" name="authors[]" class="form-control">
                                            <option value="" selected disabled>Nie wybrano</option>
                                            @foreach ($authors as $author)
                                                <option value="{{$author->id}}">{{$author->last_name}}, {{$author->first_names}}</option>
                                            @endforeach
                                            
                                   
                                        </select> 
                                                 
                                    </div>
                                    
                                        <label for="author" class="col-form-label mb-0 ml-auto mr-auto"><small>lub dodaj nowego autora:</small></label>
                                        <div class="input-group col-xs-3">
                                          <input type="text" placeholder="Imiona" class="form-control" name="newAuthorName[]">
                                          <input type="text" placeholder="Nazwisko" class="form-control" name="newAuthorLastName[]"> 
                                    </div>
                                </div>
                                <!-- DYNAMIC ELEMENT TO CLONE -->
                                <div class="control-group form-group mt-1 mb-0 dynamic-element" style="display:none">
                                    <hr>
                                    <label for="author" class="col-form-label ml-auto mr-auto"><small>Wybierz autora z bazy danych: </small></label>

                                    <div class="input-group col-xs-3">
                                        <select data-live-search="true" id="authors" name="authors[]" class="form-control">
                                            <option value="" selected disabled>Nie wybrano</option>
                                            @foreach ($authors as $author)
                                                <option value="{{$author->id}}">{{$author->last_name}}, {{$author->first_names}}</option>
                                            @endforeach
                                   
                                        </select>    
                                        <span class="input-group-btn ml-1">
                                            <button id="b1" class="btn btn-danger delete" type="button">X</button>
                                        </span>      
                                    </div>
                                    
                                        <label for="author" class="col-form-label mb-0 ml-auto mr-auto"><small>lub dodaj nowego autora:</small></label>
                                        <div class="input-group col-xs-3">
                                          <input type="text" placeholder="Imiona" class="form-control" name="newAuthorName[]">
                                          <input type="text" placeholder="Nazwisko" class="form-control" name="newAuthorLastName[]"> 
                                    </div>
                                </div>
                                <!-- END OF DYNAMIC ELEMENT -->

                                <fieldset class="pb-0 mb-0">
                                    <div class="dynamic-stuff">
                                    </div>
                                    <!-- Button -->
                                    <div class="form-group pb-0 mt-4 mb-1">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="h6 add-one pb-0 mb-0 text-danger" >+ kolejny autor</p>
                                            </div>
            
                                        </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="publisher" class="col-md-4 col-form-label text-md-right">Wydawnictwo</label>
                            <div class="col-md-6">

                                <input type="text" id="publisher" class="form-control" name="publisher">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="year" class="col-md-4 col-form-label text-md-right">Rok wydania</label>
                            <div class="col-md-6">
                                <input type="number" min="1900" max="2021" step="1" value="2020" id="year" class="form-control" name="year">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="category" class="col-md-4 col-form-label text-md-right">Kategoria</label>
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

                        <div class="form-group row">
                            <label for="numverOfItems" class="col-md-4 col-form-label text-md-right">Ilość
                                egzemplarzy</label>
                            <div class="col-md-6">
                                <input type="number" id="numverOfItems" class="form-control" name="numberOfItems">
                            </div>
                        </div>



                        <div class="row d-flex justify-content-center">

                            <button type="submit" class="btn btn-lg btn-primary">
                                Dodaj
                            </button>
                        </div>
                </div>
                

                </form>

            </div>
        </div>
    </div>
</div>
</div>



<script>

    // dynamic input for authors
    // source: https://codepen.io/llooll/pen/eVMvGR

    //Clone the hidden element and shows it
$('.add-one').click(function(){
  $('.dynamic-element').first().clone().appendTo('.dynamic-stuff').show();
  attach_delete();
});


//Attach functionality to delete buttons
function attach_delete(){
  $('.delete').off();
  $('.delete').click(function(){
    console.log("click");
    $(this).closest('.form-group').remove();
  });
}




  


// checkboxes
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

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

                 // Update the button's color
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

        // Initialization
        function init() {

            updateDisplay();

        }
        init();
    });
});
</script>
@endsection