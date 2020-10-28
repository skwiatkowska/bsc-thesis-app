@extends('layouts.user')

@section('title', 'Książka '.$book->title.' - informacje')

@section('content')

<div class="container col-lg-8 my-5">
    <div class="card my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Informacje o książce
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

            @if($book->bookItems->count() > 0)
            <div class=card-text">
                <table class="table table-bordered text-center" id="bookItemsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Uwagi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="item-table">
                        @foreach ($book->bookItems as $item)
                        <tr>
                            <td><a href="/pracownik/egzemplarze/{{$item->id}}"
                                    class="a-link-navy"><strong>{{$item->book_item_id}}</strong></a>
                            </td>
                            @if($item->is_blocked)
                            <td style="text-decoration: line-through;">
                                @else
                            <td>
                                @endif
                                @if($item->status == "Wypożyczone")
                                @foreach ($item->borrowings as $b)
                                @if(!isset($b->actual_return_date))
                                Wypożyczone
                                @endif
                                @endforeach
                                @else
                                {{$item->status}}

                                @endif
                            </td>
                            <td>
                                @if($item->status == "Wypożyczone")
                                @foreach ($item->borrowings as $b)
                                @if(!isset($b->actual_return_date))
                                Zwrot: {{date('Y-m-d', strtotime($b->due_date))}}
                                @endif
                                @endforeach
                                @elseif($item->is_blocked)
                                Zablokowane
                                @endif
                            </td>
                            <td> @if($item->status == "Dostępne" && !$item->is_blocked)
                                <a href="/egzemplarze/{{$item->id}}/wypozycz" type="button"
                                    class="btn btn-sm btn-primary">Wypożycz
                                </a>

                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        @endif
    </div>
</div>

@endsection
@section('script')
<script>
    function sortTable(){
  var rows = $('#bookItemsTable tbody tr').get();

  rows.sort(function(a, b) {
    var A = parseInt($(a).children('td').eq(0).text(),10);
    var B = parseInt($(b).children('td').eq(0).text(),10);

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