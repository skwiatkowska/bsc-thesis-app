@extends('layouts.layout-librarian')

@section('title', 'Nowa książka')

@section('content')

<div class="cotainer">
    <div class="row justify-content-center">
        <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Nowa książka</div>
                    <div class="card-body">
                        <form name="my-form" action="" method="POST">
                            <div class="form-group row">
                                <label for="title" class="col-md-4 col-form-label text-md-right">Tytuł</label>
                                <div class="col-md-6">
                                    <input type="text" id="title" class="form-control" name="title" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="author" class="col-md-4 col-form-label text-md-right">Autor</label>
                                <div class="col-md-6">
                                    <input type="text" id="author" class="form-control" name="author" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="publisher" class="col-md-4 col-form-label text-md-right">Wydawnictwo</label>
                                <div class="col-md-6">
                                    <input type="text" id="publisher" class="form-control" name="publisher" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="year" class="col-md-4 col-form-label text-md-right">Rok wydania</label>
                                <div class="col-md-6">
                                    <input type="text" id="year" class="form-control" name="year" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="numverOfItems" class="col-md-4 col-form-label text-md-right">Ilość egzemplarzy</label>
                                <div class="col-md-6">
                                    <input type="number" id="numverOfItems" class="form-control" name="numverOfItems" required>
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
@endsection