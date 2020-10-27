@extends('layouts.admin')

@section('title', 'Nowe konto Czytelnika')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mt-0">
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
                            <label for="lname"
                                class="col-md-4 col-form-label control-label text-md-right">Nazwisko</label>
                            <div class="col-md-6">
                                <input type="text" id="lname" class="form-control" name="lname" required>
                            </div>
                        </div>


                        <div class="form-group row required">
                            <label for="pesel" class="col-md-4 col-form-label control-label text-md-right">PESEL
                            </label>
                            <div class="col-md-6 col-lg-2 ">
                                <input type="text" id="pesel" class="form-control" name="pesel" required>

                            </div>
                            <label for="phone"
                                class="col-md-4 col-lg-2 mt-md-2 mt-lg-0 mx-md-0 col-form-label control-label text-md-right">Telefon
                            </label>
                            <div class="col-md-6 col-lg-2 mt-md-2 mt-lg-0">
                                <input type="text" id="phone" name="phone" class="form-control" required>

                            </div>
                        </div>


                        <div class="form-group row required">
                            <label for="email"
                                class="col-md-4 col-form-label control-label text-md-right">E-Mail</label>
                            <div class="col-md-6">
                                <input type="text" id="email" class="form-control" name="email" required>
                            </div>
                        </div>

                        <div class="form-group row required">
                            <label for="street"
                                class="col-md-4 col-form-label control-label text-md-right">Ulica</label>
                            <div class="col-md-6">
                                <input type="text" id="street" class="form-control" name="street" required>
                            </div>
                        </div>

                        <div class="form-group row required">
                            <label for="house_number" class="col-md-4 col-form-label control-label text-md-right">Nr
                                domu</label>
                            <div class="col-md-6 col-lg-2">
                                <input type="number" id="house_number" class="form-control" name="house_number"
                                    required>

                            </div>
                            <label for="zipcode"
                                class="col-md-4 col-lg-2 mt-md-2 mt-lg-0 mx-md-0 col-form-label control-label text-md-right">Kod
                                poczt.
                            </label>
                            <div class="col-md-6 col-lg-2 mt-md-2 mt-lg-0">
                                <input type="text" id="zipcode" class="form-control" name="zipcode" required>
                            </div>
                        </div>
                        <div class="form-group row required">
                            <label for="city" class="col-md-4 col-form-label control-label text-md-right">Miasto</label>
                            <div class="col-md-6">
                                <input type="text" id="city" class="form-control" name="city" required>
                            </div>
                        </div>
                        <input type="hidden" name="isModal" value="false">
                        <div class="row d-flex justify-content-center">
                            <button type="submit" class="btn btn-lg btn-primary">
                                Dodaj
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection