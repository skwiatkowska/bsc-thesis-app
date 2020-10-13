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
                            <th colspan="2">Akcja</th>
                        </tr>
                    </thead>
                    <tbody class="item-table">
                        @foreach ($book->bookItems as $item)
                        <tr>
                            <td><a href="/pracownik/ksiazki/egzemplarze/{{$item->id}}"
                                    class="a-link-navy"><strong>{{$item->bookitem_id}}</strong></a>
                            </td>
                            @if($item->is_blocked)
                            <td style="text-decoration: line-through;">
                            @else
                            <td>
                            @endif
                            {{$item->status}}
                            </td>
                            <td>
                                @if($item->status == "Wypożyczone")
                                Zwrot: ##data zwrotu##
                                @elseif($item->is_blocked)
                                Zablokowane
                                @endif
                            </td>
                            <td> @if($item->status == "Dostępne" && !$item->is_blocked)
                                <button type="button" class="btn btn-sm btn-primary">
                                    Wypożycz
                                </button>
                                @endif
                            </td>
                            <td>
                                @if($item->status == "Dostępne" && !$item->is_blocked)
                                <form>
                                    <button type="submit" title="Zablokuj" class="btn btn-sm block-item"
                                        style="color:red; background:transparent; "><i class="fa fa-ban"></i></button>
                                    <input type="hidden" name="id" value="{{$item->id}}">
                                </form>
                                @elseif($item->status != "Dostępne" && !$item->is_blocked)
                                <button title="Nie można zablokować niedostępnego egzemplarza" class="btn btn-sm"
                                    style="color:gray; background:transparent;" disabled><i
                                        class="fa fa-ban"></i></button>
                                @elseif($item->is_blocked)
                                <form>
                                    <button type="submit" title="Odblokuj" class="btn btn-sm block-item"
                                        style="background:transparent;"><i class="fa fa-unlock"></i></button>
                                    <input type="hidden" name="id" value="{{$item->id}}">
                                </form>
                                @endif

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
    //block/unlock item
$(".block-item").click(function(e){
    var rowNumber = $('.block-item').index(this);

      e.preventDefault();
      var id = $("input[name=id]").eq(rowNumber).val();
      $.ajax({
         type:'POST',
         dataType : 'json',
         url:'/pracownik/ksiazki/egzemplarze/'+ id +'/blokuj',
         data: {_token:"{{csrf_token()}}", id: id},
         success:function(data){
            location.reload();
            alert(data.success);
         },
         error: function(data){
            alert(data.responseJSON.error);
          }
    });
  });


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