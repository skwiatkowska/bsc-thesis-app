@extends('layouts.layout')

@section('title', 'Moje dane')


@section('content')
{{-- source:https://bootsnipp.com/snippets/KA5DX --}}
<div id="user" class="container profile col-lg-8 mb-5">
    <div class="row">
        <div class="col text-center mt-3">
            <h2 class="mt-3">Dane o koncie</h2>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                        aria-controls="profile" aria-selected="true">Dane osobowe</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab"
                        aria-controls="address" aria-selected="false">Dane adresowe</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="account-tab" data-toggle="tab" href="#account" role="tab"
                        aria-controls="account" aria-selected="false">Zarządzenie kontem</a>
                </li>

            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <table class="table table-hover table-sm table-properties">
                        <tr>
                            <th>Imię</th>
                            <td><a class="editable-input" id="fname">{{$user->first_name}}<i class="fa fa-pencil ml-4"></i></a></td>
                        </tr>
                        <tr>
                            <th>Nazwisko</th>
                            <td><a class="editable-input" id="lname">{{$user->last_name}}<i class="fa fa-pencil ml-4"></i></a></td>
                        </tr>
                        <tr>
                            <th>PESEL</th>
                            <td><a class="editable-input" id="pesel">{{$user->pesel}}<i class="fa fa-pencil ml-4"></i></a></td>
                        </tr>
                        <tr>
                            <th>Telefon</th>
                            <td><a class="editable-input" id="phone">{{$user->phone}}<i class="fa fa-pencil ml-4"></i></a></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><a class="editable-input" id="email">{{$user->email}}<i class="fa fa-pencil ml-4"></i></a></td>
                        </tr>

                    </table>
                </div>

                <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                    <table class="table table-hover table-sm table-properties">
                        <tr>
                            <th>Ulica i numer domu</th>
                            <td><a class="editable-input" id="street">{{$user->street}}<i class="fa fa-pencil ml-4"></i></a></td>
                        </tr>
                        <tr>
                            <th>Kod pocztowy</th>
                            <td><a class="editable-input" id="zipcode">{{$user->zipcode}}<i class="fa fa-pencil ml-4"></i></a></td>
                        </tr>
                        <tr>
                            <th>Miasto</th>
                            <td><a class="editable-input" id="city">{{$user->city}}<i class="fa fa-pencil ml-4"></i></a></td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
                    <table class="table table-hover table-sm table-properties">
                        <tr>
                            <th>Data utworzenia konta</th>
                            <td>{{$user->created_at}}</td>
                        </tr>
                        <tr>
                            <th>Hasło</th>
                            <td><button type="button" class="btn btn-sm btn-red" data-toggle="modal" data-target="#changePwdModal">Zmień hasło</button>
                            </td>
                        </tr>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="changePwdModal" tabindex="-1" role="dialog" aria-labelledby="changePwdModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="changePwdModalForm" action="/reset" method="POST">
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
                        <label for="current_password" class="col-md-4 col-form-label control-label text-md-right">Stare hasło</label>
                        <div class="col-md-6">
                            <input type="password" id="current_password" class="form-control" name="current_password" required>
                        </div>
                    </div>
                    <div class="form-group required row">
                        <label for="new_password" class="col-md-4 col-form-label control-label text-md-right">Nowe hasło</label>
                        <div class="col-md-6">
                            <input type="password" id="new_password" class="form-control" name="new_password" required>
                        </div>
                    </div>
                    <div class="form-group required row">
                        <label for="confirm_password" class="col-md-4 col-form-label control-label text-md-right">Powtórz nowe hasło</label>
                        <div class="col-md-6">
                            <input type="password" id="confirm_password" class="form-control" name="confirm_password" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    <button type="submit" id="change-pwd-btn-submit" class="btn btn-red">Zmień hasło</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- <div class="container py-5">
    <div class="card border-secondary my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Szczegóły
                <div class="ml-auto">

                </div>
            </div>
        </div>

        <div class="card-body">
            <div class=card-text">
                <ul class="list-unstyled">
                    <li><strong>Imię: </strong><a class="editable-input" id="fname">{{$user->first_name}}<i
    class="fa fa-pencil-alt ml-2"></i></a></li>
<li><strong>Nazwisko: </strong><a class="editable-input" id="lname">{{$user->last_name}}<i
            class="fa fa-pencil-alt ml-2"></i></a></li>
<li><strong>PESEL: </strong><a class="editable-input" id="pesel">{{$user->pesel}}<i
            class="fa fa-pencil-alt ml-2"></i></a></li>
<li><strong>Telefon: </strong><a class="editable-input" id="phone">{{$user->phone}}<i
            class="fa fa-pencil-alt ml-2"></i></a></li>
<li><strong>E-mail: </strong><a class="editable-input" id="email">{{$user->email}}<i
            class="fa fa-pencil-alt ml-2"></i></a></li>
<li><strong>Data utworzenia konta: </strong>{{$user->created_at}}</li>


</ul>
</div>
</div>
</div>
</div> --}}



<script>

    $.fn.editable.defaults.mode = 'inline';
    var id = {!! json_encode($user->id) !!};

    $(document).ready(
        function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.editable-input').editable({
                validate: function(value) {
                    if($.trim(value) == '')
                        return 'Podaj wartość';
                },
                type: 'text',
                placement: 'right',
                send:'always',
                pk: id,
                url: '/pracownik/czytelnicy/'+ id + '/edycja',
                ajaxOptions: {
                    dataType: 'json',
                    type: 'post'
                },
                success:function(data){
            location.reload();
            alert(data.success);
         },
            });
        }
    );
</script>

@endsection