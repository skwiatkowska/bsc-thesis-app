@extends('layouts.layout-librarian')

@section('title', 'Kategorie')

@section('content')

<div class="container col-lg-10 offset-lg-1">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="input-group pl-0">
        <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fas fa-search" aria-hidden="true"></i>
          </span>
        </div>
        <input class="form-control my-0 py-1" id="categoryListSearch" type="text" placeholder="Znajdź kategorię..."
          aria-label="Search">
        <div class="input-group-prepend">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newCategoryModal">
            <i class="fas fa-plus"></i>

          </button>

        </div>
      </div>
      <br>

      <ul class="list-group" id="categoryList">
        @foreach ($categories as $category)
          <li class="list-group-item">{{ $category->name }}</li>
        @endforeach

      </ul>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="newCategoryModal" tabindex="-1" role="dialog" aria-labelledby="newCategoryModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="/pracownik/kategorie" name="newCategoryForm">
        {{ csrf_field() }}
        <div class="modal-header">
          <h5 class="modal-title" id="newCategoryModalLabel">Nowa kategoria</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-right">Nazwa</label>
            <div class="col-md-6">
              <input type="text" id="name" class="form-control" name="name" required>
            </div>
          </div>
        </div>
        <div class="modal-footer p-3">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
          <button type="submit" class="btn btn-primary">Zapisz</button>
        </div>
      </form>
    </div>
  </div>
</div>


@endsection