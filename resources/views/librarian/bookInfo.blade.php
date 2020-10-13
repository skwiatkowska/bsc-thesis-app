@extends('layouts.admin')

@section('title', 'Książka '.$book->title.' - informacje')

@section('content')

<div class="container col-lg-10">
    <div class="card my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Szczegóły książki
                <div class="ml-auto">
                    <a href="{{$book->id}}/edycja" class="px-2" title="Edytuj"><i class="fa fa-pencil-alt"></i></a>
                    <a href="#" title="Usuń"><i class="fa fa-trash-alt"></i></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="card-text">
                <ul class="list-unstyled">
                    <li><strong>Tytuł: </strong>{{$book->title}}</li>
                    <li><strong>ISBN: </strong>{{$book->isbn}}</li>
                    <li><strong>Autorzy: </strong>
                        @foreach ($book->authors as $author)
                        <a href="/pracownik/autorzy/{{$author->id}}" class="a-link-navy">{{$author->last_name}},
                            {{$author->first_names}}</a>
                        {{ $loop->last ? '' : ' •' }}
                        @endforeach
                    </li>
                    <li><strong>Wydawnictwo: </strong><a href="/pracownik/wydawnictwa/{{$book->publisher->id}}"
                            class="a-link-navy">{{$book->publisher->name}}</a>
                    </li>
                    <li><strong>Rok wydania: </strong>{{$book->publication_year}}</li>
                    <li><strong>Kategorie: </strong>
                        @foreach ($book->categories as $category)
                        {{$category->name}}
                        {{ $loop->last ? '' : ' •' }}
                        @endforeach
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Egzemplarze ({{$book->book_items_number}})
                <div class="ml-auto">
                    <a href="#" class="px-2" title="Edytuj"><i class="fa fa-pencil-alt"></i></a>
                    <a href="#" title="Usuń"><i class="fa fa-trash-alt"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body ">
            <div class=card-text">
                <table class="table table-bordered text-center" id="bookItemsTable">
                    <thead>
                        <tr>
                          <th>ID</th>
                          <th>Status</th>
                          <th>Uwagi</th>
                          <th>Akcja</th>
                        </tr>
                      </thead>
                    <tbody class="item-table">
                      @foreach ($book->bookItems as $item)
                      <tr>
                        <td><a href="/pracownik/ksiazki/egzemplarze/{{$item->id}}"
                            class="a-link-navy"><strong>{{$item->bookitem_id}}</strong></a>
                        </td>
                        <td>{{$item->status}}
                        </td>
                        <td>
                            @if($item->status == "Wypożyczone")
                            Zwrot: ##data zwrotu##
                            @else
                            -
                            @endif
                        </td>
                     <td><button type="button" class="btn btn-sm btn-primary"> 
                         @if($item->status == "Dostępne")
                         Wypożycz
                         @endif
                        </button>
                        
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
            
            </div>
        </div>
    </div>
</div>
<script>
    function sortTable(){
  var rows = $('#bookItemsTable tbody tr').get();

  rows.sort(function(a, b) {

  var A = $(a).children('td').eq(0).text().toUpperCase();
  var B = $(b).children('td').eq(0).text().toUpperCase();

  if(A < B) {
    return -1;
  }

  if(A > B) {
    return 1;
  }

  return 0;

  });

  $.each(rows, function(index, row) {
    $('#bookItemsTable').children('tbody').append(row);
  });
}

sortTable();
    </script>
@endsection