@extends('layouts.layout-librarian')

@section('title', 'Nowe konto Czytelnika')

@section('content')

<div class="cotainer">
    <div class="row justify-content-center">
        <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Nowy Czytelnik</div>
                    <div class="card-body">
                        <form name="my-form" action="" method="POST">
                            <div class="form-group row">
                                <label for="fname" class="col-md-4 col-form-label text-md-right">Imię</label>
                                <div class="col-md-6">
                                    <input type="text" id="fname" class="form-control" name="fname">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lname" class="col-md-4 col-form-label text-md-right">Nazwisko</label>
                                <div class="col-md-6">
                                    <input type="text" id="lname" class="form-control" name="lname">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="pesel" class="col-md-4 col-form-label text-md-right">PESEL</label>
                                <div class="col-md-6">
                                    <input type="text" id="pesel" class="form-control" name="pesel">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>
                                <div class="col-md-6">
                                    <input type="text" id="email" class="form-control" name="email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">Numer telefonu</label>
                                <div class="col-md-6">
                                    <input type="text" id="phone" name="phone" class="form-control">
                                </div>
                            </div>


                                <div class="col-md-6 offset-md-5">
                                    <button type="submit" class="btn btn-primary">
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