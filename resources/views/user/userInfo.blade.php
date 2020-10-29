@extends('layouts.user')

@section('title', 'Moje dane')


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card my-3 form-card">
                <div class="card-header">Dane o koncie</div>
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <nav>
                                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                        <a class="nav-item nav-link active" id="nav-personal-tab" data-toggle="tab"
                                            href="#nav-personal" role="tab" aria-controls="nav-personal"
                                            aria-selected="true">Dane osobowe</a>
                                        <a class="nav-item nav-link" id="nav-address-tab" data-toggle="tab"
                                            href="#nav-address" role="tab" aria-controls="nav-address"
                                            aria-selected="false">Dane adresowe</a>
                                        <a class="nav-item nav-link" id="nav-account-tab" data-toggle="tab"
                                            href="#nav-account" role="tab" aria-controls="nav-account"
                                            aria-selected="false">Zarządzanie kontem</a>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-personal" role="tabpanel"
                                        aria-labelledby="nav-personal-tab">
                                        <div class=" col-md-8 mx-auto">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td>Imię:</td>
                                                        <td>{{$user->first_name}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nazwisko:</td>
                                                        <td>{{$user->last_name}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>PESEL:</td>
                                                        <td>{{$user->pesel}}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Telefon:</td>
                                                        <td>{{$user->phone}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>E-mail:</td>
                                                        <td>{{$user->email}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-address" role="tabpanel"
                                        aria-labelledby="nav-address-tab">
                                        <div class=" col-md-8 mx-auto">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td> Ulica:</td>
                                                        <td>{{$user->street}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:50%">
                                                            Numer domu:</td>
                                                        <td>{{$user->house_number}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Kod pocztowy:</td>
                                                        <td>{{$user->zipcode}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Miasto:</td>
                                                        <td>{{$user->city}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-account" role="tabpanel"
                                        aria-labelledby="nav-account-tab">
                                        <div class=" col-md-8 mx-auto">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td style="width:50%"> Data utworzenia konta:</td>
                                                        <td>{{date('Y-m-d', strtotime($user->created_at))}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Hasło:</td>
                                                        <td><button type="button" class="btn btn-sm btn-secondary"
                                                                data-toggle="modal" data-target="#changePwdModal">Zmień
                                                                hasło</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dane:</td>
                                                        <td>
                                                            <a href="/zmien-dane" type="button"
                                                                class="btn btn-sm btn-secondary">Zmień dane</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Konto:</td>
                                                        <td>
                                                            <form action="/usun-konto" method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć swoje konto na stałe?\nWszystkie Twoje dane i historia zostaną usunięte.\nAkcja jest nieodwracalna.');">
                                                                {{ csrf_field() }}
                                                            <button type="submit" class="btn btn-sm btn-danger">Usuń
                                                                konto</button>
                                                            </form>
                                                            </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="changePwdModal" tabindex="-1" role="dialog" aria-labelledby="changePwdModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="changePwdModalForm" action="/zmien-haslo" method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="changePwdModalLabel">Zmień hasło
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row required">
                        <label for="current_password" class="col-md-4 col-form-label control-label text-md-right">Stare
                            hasło</label>
                        <div class="col-md-6">
                            <input type="password" id="current_password" class="form-control" name="current_password"
                                required>
                        </div>
                    </div>
                    <div class="form-group required row">
                        <label for="new_password" class="col-md-4 col-form-label control-label text-md-right">Nowe
                            hasło</label>
                        <div class="col-md-6">
                            <input type="password" id="new_password" class="form-control" name="new_password" required>
                        </div>
                    </div>
                    <div class="form-group required row">
                        <label for="confirm_password"
                            class="col-md-4 col-form-label control-label text-md-right">Powtórz nowe hasło</label>
                        <div class="col-md-6">
                            <input type="password" id="confirm_password" class="form-control" name="confirm_password"
                                required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    <button type="submit" id="change-pwd-btn-submit" class="btn btn-primary">Zmień hasło</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection