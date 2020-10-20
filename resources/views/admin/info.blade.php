@extends('layouts.admin')

@section('title', 'Informacje o bibliotece')

@section('content')

<div class="container mb-5 pb-5">
    <div class="row mb-5 px-5">
        <div class="col">
            <div class="card border-danger mb-3">
                <div class="card-body text-danger">
                    <h5 class="card-title">Kontakt</h5>
                    <p class="card-text">Informacje o kontakcie</p>
                </div>
            </div>
        </div>


        <div class="col">
            <div class="card border-danger mb-3">
                <div class="card-body text-danger">
                    <h5 class="card-title">Godziny otwarcia</h5>
                    <p class="card-text">Informacje o godzinach otwarcia</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection