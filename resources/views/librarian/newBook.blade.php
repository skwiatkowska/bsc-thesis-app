@extends('layouts.layout-librarian')

@section('title', 'Nowa książka')

@section('content')

<div class="cotainer">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Nowa książka</div>
                <div class="card-body">
                    <form name="newBookForm" action="/pracownik/ksiazki" method="POST">
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
                                <div class="col-md-12">

                                    <input autocomplete="off" class="input" id="field1" name="prof1" type="text"
                                        placeholder="Type something" data-items="8" /><button id="b1"
                                        class="btn add-more" type="button">+</button>
                                </div>
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
                                <input type="text" id="year" class="form-control" name="year">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="category" class="col-md-4 col-form-label text-md-right">Kategoria</label>
                            <div class="col-md-6">
                                <input type="text" id="category" class="form-control" name="category">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="numverOfItems" class="col-md-4 col-form-label text-md-right">Ilość
                                egzemplarzy</label>
                            <div class="col-md-6">
                                <input type="number" id="numverOfItems" class="form-control" name="numverOfItems">
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
    $(document).ready(function(){
    var next = 1;
    $(".add-more").click(function(e){
        e.preventDefault();
        var addto = "#field" + next;
        var addRemove = "#field" + (next);
        next = next + 1;
        var newIn = '<input autocomplete="off" class="input form-control" id="field' + next + '" name="field' + next + '" type="text">';
        var newInput = $(newIn);
        var removeBtn = '<button id="remove' + (next - 1) + '" class="btn btn-danger remove-me" >-</button></div><div id="field">';
        var removeButton = $(removeBtn);
        $(addto).after(newInput);
        $(addRemove).after(removeButton);
        $("#field" + next).attr('data-source',$(addto).attr('data-source'));
        $("#count").val(next);  
        
            $('.remove-me').click(function(e){
                e.preventDefault();
                var fieldNum = this.id.charAt(this.id.length-1);
                var fieldID = "#field" + fieldNum;
                $(this).remove();
                $(fieldID).remove();
            });
    });
    

    
});

</script>
@endsection