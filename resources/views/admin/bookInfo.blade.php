@extends('layouts.admin')

@section('title', 'Książka '.$book->title.' - informacje')

@section('content')

<div class="container col-lg-10">
    <div class="card my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Szczegóły książki
                <div class="ml-auto row">
                    <a href="{{$book->id}}/edycja" class="btn px-2 my-auto" title="Edytuj"><i
                            class="fa fa-pencil-alt"></i></a>

                    <form action="/pracownik/ksiazki/{{$book->id}}/usun" method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć na stałe?');">
                        {{ csrf_field() }}
                        <button type="submit" title="Usuń książkę na stałe"
                            class="btn delete-book" style="background:transparent;"><i
                                class="fa fa-trash-alt"></i></button>
                        <input type="hidden" name="id" value="{{$book->id}}">
                    </form>
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
                Egzemplarze ({{$book->bookItems->count()}})
                <div class="ml-auto">
                    <a href="#" class="px-2" title="Dodaj" data-toggle="modal" data-target="#newBookItemModal"><i
                            class="fa fa-plus"></i></a>
                </div>
            </div>
        </div>
        @if($book->bookItems->count() > 0)
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
                                Wypożyczone:
                                <br>
                                <a href="/pracownik/czytelnicy/{{$b->user->id}}"
                                    class="a-link-navy">{{$b->user->first_name}} {{$b->user->last_name}}</a>

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
                                @elseif($item->status == "Zarezerwowane")
                                Rezerwacja do: {{date('Y-m-d', strtotime($item->reservations->first()->due_date))}}

                                @elseif($item->is_blocked)
                                Zablokowane
                                @endif
                            </td>
                            <td> @if($item->status == "Dostępne" && !$item->is_blocked)
                                <a href="/pracownik/egzemplarze/{{$item->id}}/wypozycz" type="button"
                                    class="btn btn-sm btn-primary">Wypożycz
                                </a>
                                @elseif($item->status == "Zarezerwowane")
                                <form>
                                    <button type="submit" title="Anuluj rezerwację"
                                        class="btn btn-sm btn-secondary mb-2 cancel-reservation">Anuluj
                                        </button>
                                    <input type="hidden" name="id"
                                        value="{{$item->reservations->first()->id}}">
                                </form>
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
                                <input type="hidden" name="id" value="{{$item->id}}">
                                @elseif($item->is_blocked)
                                <div class="row justify-content-lg-center">
                                    <form>
                                        <button type="submit" title="Odblokuj" class="btn btn-sm block-item"
                                            style="background:transparent;"><i class="fa fa-unlock"></i></button>
                                        <input type="hidden" name="id" value="{{$item->id}}">
                                    </form>
                                    <form>
                                        <button type="submit" title="Usuń na stałe"
                                            class="btn btn-sm delete-item" style="background:transparent;"><i
                                                class="fa fa-trash"></i></button>
                                        <input type="hidden" name="id" value="{{$item->id}}">
                                    </form>
                                </div>
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
<div class="modal fade" id="newBookItemModal" tabindex="-1" role="dialog" aria-labelledby="newBookItemModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="newBookItemForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="newBookItemModalLabel">Kolejny egzemplarz</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row required">
                        <label for="order" class="col-md-6 col-form-label control-label text-md-right">Numer
                            porządkowy</label>
                        <div class="col-md-4">
                            <input type="number" id="order" class="form-control" name="order"
                                min="{{$book->bookItems->count()+1}}" value="{{$book->bookItems->count()+1}}" required>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="bookId" name="bookId" value="{{$book->id}}">
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    <button type="submit" id="new-item-btn-submit" class="btn btn-primary">Dodaj</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    //submit new book item form in modal
$("#new-item-btn-submit").click(function(e){
      e.preventDefault();
      var order = $("input[name=order]").val();
      var bookId = $("input[name=bookId]").val();
     
      $.ajax({
         type:'POST',
         dataType : 'json',
         url:'/pracownik/ksiazki/'+ bookId + '/nowy-egzemplarz',
         data: {_token:"{{csrf_token()}}", order: order, bookId: bookId},
         success:function(data){
            console.log(data);
            location.reload();
            alert(data.success);
         },
         error: function(data){
            //  console.log(data);
            // alert(data.responseJSON.error);
          }
    });

  });


      //cancel a reservation
      $(".cancel-reservation").click(function(e){
        e.preventDefault();
         var confirmed = confirm('Czy na pewno chcesz anulować rezerwację?');

        if (confirmed) {
        var id = $("input[name=id]", this.form).val();
        $.ajax({
            type:'POST',
            dataType : 'json',
            url:'/pracownik/rezerwacje/anuluj',
            data: {_token:"{{csrf_token()}}", id: id},
            success:function(data){
                location.reload();
                alert(data.success);
            },
        });
    }
    });

    //block/unlock item
$(".block-item").click(function(e){
      e.preventDefault();
      var id = $("input[name=id]", this.form).val();
      console.log(id);
      $.ajax({
         type:'POST',
         dataType : 'json',
         url:'/pracownik/egzemplarze/'+ id +'/blokuj',
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

      //delete an item
$(".delete-item").click(function(e){
      e.preventDefault();
      var confirmed = confirm('Czy na pewno chcesz usunąć na stałe?');
      if(confirmed){
      var id = $("input[name=id]", this.form).val();
      console.log(id);
      $.ajax({
         type:'POST',
         dataType : 'json',
         url:'/pracownik/egzemplarze/'+ id +'/usun',
         data: {_token:"{{csrf_token()}}", id: id},
         success:function(data){
            location.reload();
            alert(data.success);
         },
         error: function(data){
            alert(data.responseJSON.error);
          }
    });
    }
  });


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