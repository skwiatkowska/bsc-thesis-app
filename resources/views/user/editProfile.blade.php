@extends('layouts.user')

@section('title', 'Edytuj swoje dane')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card my-3 form-card">
                <div class="card-header">Zmień dane swojego konta</div>
                <div class="card-body">
                    <form name="newUserForm" action="/zmien-dane" method="POST">
                        <input type="hidden" name="_method" value="PUT">

                        {{ csrf_field() }}

                        <div class="form-group row required">
                            <label for="first_name" class="col-md-4 col-form-label control-label text-md-right">Imię</label>
                            <div class="col-md-6">
                                <input type="text" id="first_name" class="form-control" name="first_name"
                                    value="{{$user->first_name}}" required>
                            </div>
                        </div>
                        <div class="form-group row required">
                            <label for="last_name"
                                class="col-md-4 col-form-label control-label text-md-right">Nazwisko</label>
                            <div class="col-md-6">
                                <input type="text" id="last_name" class="form-control" name="last_name"
                                    value="{{$user->last_name}}" required>
                            </div>
                        </div>


                        <div class="form-group row required">
                            <label for="pesel" class="col-md-4 col-form-label control-label text-md-right">PESEL
                            </label>
                            <div class="col-md-6 col-lg-2">
                                <input type="text" id="pesel" class="form-control" name="pesel" value="{{$user->pesel}}"
                                    required>

                            </div>
                            <label for="phone"
                                class="col-md-4 col-lg-2 mt-md-2 mt-lg-0 mx-md-0 col-form-label control-label text-md-right">Telefon
                            </label>
                            <div class="col-md-6 col-lg-2 mt-md-2 mt-lg-0">
                                <input type="text" id="phone" name="phone" class="form-control" value="{{$user->phone}}"
                                    required>

                            </div>
                        </div>


                        <div class="form-group row required">
                            <label for="email"
                                class="col-md-4 col-form-label control-label text-md-right">E-Mail</label>
                            <div class="col-md-6">
                                <input type="text" id="email" class="form-control" name="email" value="{{$user->email}}"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row required">
                            <label for="street"
                                class="col-md-4 col-form-label control-label text-md-right">Ulica</label>
                            <div class="col-md-6">
                                <input type="text" id="street" class="form-control" name="street"
                                    value="{{$user->street}}" required>
                            </div>
                        </div>

                        <div class="form-group row required">
                            <label for="house_number" class="col-md-4 col-form-label control-label text-md-right">Nr
                                domu</label>
                            <div class="col-md-6 col-lg-2">
                                <input type="text" id="house_number" class="form-control" name="house_number"
                                    value="{{$user->house_number}}" required>

                            </div>
                            <label for="zipcode"
                                class="col-md-4 col-lg-2 mt-md-2 mt-lg-0 mx-md-0 col-form-label control-label text-md-right">Kod
                                pocztowy
                            </label>
                            <div class="col-md-6 col-lg-2 mt-md-2 mt-lg-0">
                                <input type="text" id="zipcode" class="form-control" name="zipcode"
                                    value="{{$user->zipcode}}" required>
                            </div>
                        </div>
                        <div class="form-group row required">
                            <label for="city" class="col-md-4 col-form-label control-label text-md-right">Miasto</label>
                            <div class="col-md-6">
                                <input type="text" id="city" class="form-control" name="city" value="{{$user->city}}"
                                    required>
                            </div>
                        </div>
                        <input type="hidden" name="isModal" value="false">
                        <div class="row d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary mr-4">
                                Zapisz zmiany
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="goBack()">
                                Anuluj
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection