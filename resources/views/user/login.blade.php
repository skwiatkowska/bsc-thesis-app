@extends('layouts.user')

@section('title', 'Logowanie')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center my-4">
        <div class="col-md-6">
            <div class="card my-3 form-card">
                <div class="card-header">Logowanie</div>
                <div class="card-body">
                    <form action="/logowanie" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group row required">
                            <label for="email" class="col-md-4 col-form-label control-label text-md-right">Email</label>
                            <div class="col-md-6">
                                <input type="text" id="email" class="form-control" value="{{ old('email') }}"
                                    name="email" required>
                            </div>
                        </div>
                        <div class="form-group row required">
                            <label for="password"
                                class="col-md-4 col-form-label control-label text-md-right">Hasło</label>
                            <div class="col-md-6">
                                <input type="password" id="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        <div class="form-group row">

                            <label for="remember" class="col-form-label control-label mx-auto"><input type="checkbox"
                                    id="remember" class="mr-2"><strong>Zapamiętaj mnie</strong></label>
                        </div>
                        <input type="hidden" name="isModal" value="false">

                        <div class="row d-flex justify-content-center">
                            <button type="submit" class="btn btn-lg btn-primary">
                                Zaloguj się
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection