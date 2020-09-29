@extends('layouts.layout-librarian')

@section('title', 'Nowe konto Czytelnika')

@section('content')

<div class="cotainer">
    <div class="row justify-content-center">
        <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Nowy Czytelnik</div>
                    <div class="card-body">
                        <form name="newUserForm" action="/pracownik/czytelnicy/nowy" method="POST">
                            {{ csrf_field() }}

                            <div class="form-group row required">
                                <label for="fname" class="col-md-4 col-form-label control-label text-md-right">Imię</label>
                                <div class="col-md-6">
                                    <input type="text" id="fname" class="form-control" name="fname" required>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <label for="lname" class="col-md-4 col-form-label control-label text-md-right">Nazwisko</label>
                                <div class="col-md-6">
                                    <input type="text" id="lname" class="form-control" name="lname" required>
                                </div>
                            </div>

                            <div class="form-group row required">
                                <label for="pesel" class="col-md-4 col-form-label control-label text-md-right">PESEL</label>
                                <div class="col-md-6">
                                    <input type="text" id="pesel" class="form-control" name="pesel" required>
                                </div>
                            </div>

                            <div class="form-group row required">
                                <label for="email" class="col-md-4 col-form-label control-label text-md-right">E-Mail</label>
                                <div class="col-md-6">
                                    <input type="text" id="email" class="form-control" name="email" required>
                                </div>
                            </div>

                            <div class="form-group row required">
                                <label for="phone" class="col-md-4 col-form-label control-label text-md-right">Numer telefonu</label>
                                <div class="col-md-6">
                                    <input type="text" id="phone" name="phone" class="form-control" required>
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