@extends('layouts.admin')

@section('title', 'Egzemplarz '.$item->book_item_id.' - '.$item->book->title)

@section('content')

<div class="container col-lg-10">
    <div class="col-sm-1">
        <a href="/pracownik/ksiazki/{{$item->book->id}}" type="button" class="btn btn-sm btn-secondary btn-rounded"><i
                class="fa fa-arrow-left"></i> Powrót do książki
        </a>
    </div>
    <div class="card my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Szczegóły egzemplarza
            </div>
        </div>

        <div class="card-body">
            <div class=card-text">
                <ul class="list-unstyled">
                    <li><strong>Tytuł: </strong><a href="/pracownik/ksiazki/{{$item->book->id}}"><strong
                                    class="a-link-navy">{{$item->book->title}}</strong></a></li>
                    <li><strong>Numer egzemplarza: </strong>{{$item->book_item_id}}</li>
                    <li><strong>Autorzy: </strong>
                        @foreach ($item->book->authors as $author)
                        <a href="/pracownik/autorzy/{{$author->id}}" class="a-link-navy">{{$author->last_name}},
                            {{$author->first_names}}</a>
                        {{ $loop->last ? '' : ' •' }}
                        @endforeach
                    </li>
                    <li><strong>Wydawnictwo: </strong><a href="/pracownik/wydawnictwa/{{$item->book->publisher->id}}"
                            class="a-link-navy">{{$item->book->publisher->name}}</a>
                    </li>
                    <li><strong>Rok wydania: </strong>{{$item->book->publication_year}}</li>
                    <li><strong>Kategorie: </strong>
                        @foreach ($item->book->categories as $category)
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
                Rezerwacje i wypożyczenia
            </div>
        </div>

        <div class="card-body">
            <div class=card-text">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="reservation-tab" data-toggle="tab" href="#reservation" role="tab"
                            aria-controls="reservation" aria-selected="true">Rezerwacje</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="borrowing-tab" data-toggle="tab" href="#borrowing" role="tab"
                            aria-controls="borrowing" aria-selected="false">Aktualne wypożyczenia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab"
                            aria-controls="history" aria-selected="true">Historia</a>
                    </li>

                </ul>
                <div class="tab-content mt-3">
                    <div class="tab-pane fade" id="reservation" role="tabpanel" aria-labelledby="reservation-tab">...
                    </div>
                    <div class="tab-pane fade show active" id="borrowing" role="tabpanel"
                        aria-labelledby="borrowing-tab">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Czytelnik</th>
                                    <th>Wypożyczono</th>
                                    <th>Zwrot</th>
                                    <th colspan="2">Akcja</th>
                                </tr>
                            </thead>
                            <tbody class="item-table">
                                @foreach ($item->borrowings as $borrowing)
                                @if(!isset($borrowing->actual_return_date))
                                <tr>
                                    <td> <a href="/pracownik/czytelnicy/{{$borrowing->user->id}}"
                                        class="a-link-navy"><strong>{{$borrowing->user->first_name}} {{$borrowing->user->last_name}}</strong></a>
                                </td>
                                    <td>{{date('Y-m-d', strtotime($borrowing->borrow_date))}}
                                    </td>
                                    <td>{{date('Y-m-d', strtotime($borrowing->due_date))}}
                                    </td>
                                    <td>
                                        @if(!$borrowing->was_prolonged)
                                        <form>
                                            <button type="submit" onclick="confirmProlongation()"
                                                title="Jednorazowo przedłuż czas oddania o 1 miesiąć"
                                                class="btn btn-sm btn-light prolong-book">Prolonguj</button>
                                            <input type="hidden" name="id" value="{{$borrowing->bookItem->id}}">
                                        </form>
                                        @else
                                        <button type="submit" title="Brak możliwości ponownej prolongaty"
                                            class="btn btn-sm btn-light prolong-book" disabled>Prolonguj</button>
                                        @endif </td>
                                    <td>
                                        @if($borrowing->bookItem->status == "Wypożyczone")
                                        <button type="button" title="Zwrot" class="btn btn-sm btn-primary mb-2"
                                           >Zwrot</button>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Czytelnik</th>
                                    <th>Wypożyczono</th>
                                    <th>Zwrócono</th>
                                    <th>Opłata</th>
                                </tr>
                            </thead>
                            <tbody class="item-table">
                                @foreach ($item->borrowings as $borrowing)
                                @if(isset($borrowing->actual_return_date))
                                <tr>
                                    <td> <a href="/pracownik/czytelnicy/{{$borrowing->user->id}}"
                                            class="a-link-navy"><strong>{{$borrowing->user->first_name}} {{$borrowing->user->last_name}}</strong></a>
                                    </td>
                                    <td>{{date('Y-m-d', strtotime($borrowing->borrow_date))}}
                                    </td>
                                    <td>{{date('Y-m-d', strtotime($borrowing->actual_return_date))}}
                                    </td>
                                    <td>
                                        {{isset($borrowing->overdue_fee)? $borrowing->overdue_fee : '-'}}
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
     $('table').each(function() {
  if($(this).find('tr').children("td").length == 0) {
      $(this).hide();
      $( "<h6 class='text-center'>Brak</h6>" ).insertBefore(this);
  }
});
        //prolong a book
        $(".prolong-book").click(function(e){
        e.preventDefault();
        var id = $("input[name=id]", this.form).val();
        $.ajax({
            type:'POST',
            dataType : 'json',
            url:'/pracownik/egzemplarze/'+id+'/prolonguj',
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

    </script>
@endsection