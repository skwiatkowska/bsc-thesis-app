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
                    <li><strong>Tytuł: </strong>"{{$book->title}}"</li>
                    <li><strong>ISBN: </strong>{{$book->isbn}}</li>
                    <li><strong>Autorzy: </strong>
                        @foreach ($book->authors as $author)
                        <a href="/autorzy/{{$author->id}}" class="a-link-navy">{{$author->last_name}},
                            {{$author->first_names}}</a>
                        {{ $loop->last ? '' : ' •' }}
                        @endforeach
                    </li>
                    <li><strong>Wydawnictwo: </strong><a href="/wydawnictwa/{{$book->publisher->id}}"
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
                            <td><strong>{{$item->book_item_id}}</strong>
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
                                @elseif($item->status == "Zarezerwowane")
                                Rezerwacja ważna do:
                                {{date('Y-m-d', strtotime($item->reservations->first()->due_date))}}
                                @elseif($item->is_blocked)
                                Zablokowane
                                @endif
                            </td>
                            <td> @if($item->status == "Dostępne" && !$item->is_blocked)
                                @auth
                                <button type=" button" class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#newReservationModal-{{$item->id}}">Zarezerwuj
                                </button>
                                @endauth
                                @guest
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#loginModal">Zarezerwuj
                                </button>
                                @endguest
                                @endif
                            </td>
                        </tr>
                        @auth
                        <div class="modal fade" id="newReservationModal-{{$item->id}}" tabindex="-1" role="dialog"
                            aria-labelledby="newReservationModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form name="newBookingConfirmForm" action="/zarezerwuj" method="POST">
                                        {{ csrf_field() }}
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="newReservationModalLabel">Potwierdź rezerwację
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            </button>
                                        </div>
                                        <div class="modal-body pt-0">
                                            <div class="form-group row">
                                                <label
                                                    class="col-md-6 col-form-label control-label text-md-right"><strong>Tytuł:</strong>
                                                </label>
                                                <label class="col-md-6 col-form-label control-label text-md-left">
                                                    "<i>{{$item->book->title}}</i> "
                                                </label>
                                                <label
                                                    class="col-md-6 col-form-label control-label text-md-right"><strong>Autorzy:</strong>
                                                </label>
                                                <label class="col-md-6 col-form-label control-label text-md-left">
                                                    @foreach ($book->authors as $author)
                                                    {{$author->last_name}}, {{$author->first_names}}
                                                    {{ $loop->last ? '' : ' •' }}
                                                    @endforeach
                                                </label>
                                                <label
                                                    class="col-md-6 col-form-label control-label text-md-right"><strong>Egzemplarz:</strong>
                                                </label>
                                                <label class="col-md-6 col-form-label control-label text-md-left">
                                                    {{$item->book_item_id}}
                                                </label>
                                                <label
                                                    class="col-md-6 col-form-label control-label text-md-right"><strong>Ważność
                                                        rezerwacji:</strong>
                                                </label>
                                                <label class="col-md-6 col-form-label control-label text-md-left">
                                                    {{date('Y-m-d', strtotime( "+3 days"))}}

                                                </label>

                                            </div>
                                        </div>
                                        <input type="hidden" name="bookItemId" value="{{$item->id}}">

                                        <div class="modal-footer p-3">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Zamknij</button>
                                            <button type="submit" id="confirm-booking-btn-submit"
                                                class="btn btn-primary">Potwierdź</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endauth
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        @endif
    </div>
</div>
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="newBookingConfirmForm" action="/logowanie" method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Logowanie
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row required">
                        <label for="email" class="col-md-4 col-form-label control-label text-md-right">Email</label>
                        <div class="col-md-6">
                            <input type="text" id="email" class="form-control" value="{{ old('email') }}" name="email"
                                required>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="password" class="col-md-4 col-form-label control-label text-md-right">Hasło</label>
                        <div class="col-md-6">
                            <input type="password" id="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="remember" class="col-form-label control-label mx-auto"><input type="checkbox"
                                id="remember" class="mr-2"><strong>Zapamiętaj mnie</strong></label>
                    </div>
                </div>
                <input type="hidden" name="isModal" value="true">

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    <button type="submit" id="confirm-booking-btn-submit" class="btn btn-primary">Zaloguj się</button>
                </div>
            </form>
        </div>
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