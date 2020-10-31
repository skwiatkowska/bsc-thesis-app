@extends('layouts.user')

@section('title', 'Moje książki')


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card my-3 form-card">
                <div class="card-header">Moje książki</div>
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <nav>
                                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                        <a class="nav-item nav-link active" id="nav-reservation-tab" data-toggle="tab"
                                            href="#nav-reservation" role="tab" aria-controls="nav-reservation"
                                            aria-selected="true">Rezerwacje</a>
                                        <a class="nav-item nav-link" id="nav-borrowing-tab" data-toggle="tab"
                                            href="#nav-borrowing" role="tab" aria-controls="nav-borrowing"
                                            aria-selected="false">Wypożyczenia</a>
                                        <a class="nav-item nav-link" id="nav-history-tab" data-toggle="tab"
                                            href="#nav-history" role="tab" aria-controls="nav-history"
                                            aria-selected="false">Historia</a>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-reservation" role="tabpanel"
                                        aria-labelledby="nav-reservation-tab">
                                        <div class=" col-md-10 mx-auto mt-5">
                                            <table class="table table-striped table-bordered text-center mt-1">
                                                <thead>
                                                    <tr>
                                                        <th>Książka</th>
                                                        <th>Egzemplarz</th>
                                                        <th>Autorzy</th>
                                                        <th>Data rezerwacji</th>
                                                        <th>Data wygaśnięcia</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="item-table">
                                                    @foreach ($user->reservations as $reservation)
                                                    @if(!isset($reservation->actual_return_date))
                                                    <tr>
                                                        <td> <a href="/ksiazki/{{$reservation->bookItem->book->id}}"
                                                                class="a-link-navy"><strong>{{$reservation->bookItem->book->title}}</strong></a>
                                                        </td>
                                                        <td>
                                                            <a href="/egzemplarze/{{$reservation->bookItem->id}}"
                                                                class="a-link-navy">{{$reservation->bookItem->book_item_id}}</a>
                                                        </td>
                                                        <td>
                                                            @foreach ($reservation->bookItem->book->authors as $author)
                                                            <a href="/autorzy/{{$author->id}}" class="a-link-navy">
                                                                {{$author->last_name.', '.$author->first_names}}
                                                            </a>
                                                            {{ $loop->last ? '' : ' •' }}
                                                            @endforeach
                                                        </td>
                                                        <td>{{date('Y-m-d', strtotime($reservation->reservation_date))}}
                                                        </td>
                                                        <td>{{date('Y-m-d', strtotime($reservation->due_date))}}
                                                        </td>
                                                        <td>
                                                            <form>
                                                                <button type="submit" title="Anuluj rezerwację"
                                                                    class="btn btn-sm btn-secondary mb-2 cancel-reservation">Anuluj
                                                                    </button>
                                                                <input type="hidden" name="id"
                                                                    value="{{$reservation->id}}">
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @endif

                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-borrowing" role="tabpanel"
                                        aria-labelledby="nav-borrowing-tab">
                                        <div class=" col-md-10 mx-auto mt-5">
                                            <table class="table table-bordered text-center">
                                                <thead>
                                                    <tr>
                                                        <th>Tytuł książki</th>
                                                        <th>Autorzy</th>
                                                        <th>Wypożyczono</th>
                                                        <th>Zwrot</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="item-table">
                                                    @foreach ($user->borrowings as $borrowing)
                                                    @if(!isset($borrowing->actual_return_date))
                                                    <tr>
                                                        <td> <a href="/ksiazki/{{$borrowing->bookItem->book->id}}"
                                                                class="a-link-navy"><strong>{{$borrowing->bookItem->book->title}}</strong></a>
                                                            <a href="/egzemplarze/{{$borrowing->bookItem->id}}"
                                                                class="a-link-navy">egzemplarz
                                                                {{$borrowing->bookItem->book_item_id}}</a>
                                                        </td>
                                                        <td>
                                                            @foreach ($borrowing->bookItem->book->authors as $author)
                                                            <a href="/autorzy/{{$author->id}}"
                                                                class="a-link-navy">{{$author->last_name}},
                                                                {{$author->first_names}}</a>
                                                            {{ $loop->last ? '' : ' •' }}
                                                            @endforeach
                                                        </td>
                                                        <td>{{date('Y-m-d', strtotime($borrowing->borrow_date))}}
                                                        </td>
                                                        <td>{{date('Y-m-d', strtotime($borrowing->due_date))}}
                                                        </td>
                                                        <td>
                                                            @if(!$borrowing->was_prolonged)
                                                            <form>
                                                                <button type="submit"
                                                                    title="Jednorazowo przedłuż czas oddania o 1 miesiąć"
                                                                    class="btn btn-sm btn-secondary prolong-book">Prolonguj</button>
                                                                <input type="hidden" name="id"
                                                                    value="{{$borrowing->bookItem->id}}">
                                                            </form>
                                                            @else
                                                            <button type="submit"
                                                                title="Brak możliwości ponownej prolongaty"
                                                                class="btn btn-sm btn-light prolong-book"
                                                                disabled>Prolonguj</button>
                                                            @endif </td>

                                                    </tr>
                                                    @endif


                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-history" role="tabpanel"
                                        aria-labelledby="nav-history-tab">
                                        <div class=" col-md-10 mx-auto mt-5">
                                            <table class="table table-bordered text-center">
                                                <thead>
                                                    <tr>
                                                        <th>Tytuł książki</th>
                                                        <th>Egzemplarz</th>
                                                        <th>Autorzy</th>
                                                        <th>Wypożyczono</th>
                                                        <th>Zwrócono</th>
                                                        <th>Opłata</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="item-table">
                                                    @foreach ($user->borrowings as $borrowing)
                                                    @if(isset($borrowing->actual_return_date))
                                                    <tr>
                                                        <td> <a href="/ksiazki/{{$borrowing->bookItem->book->id}}"
                                                                class="a-link-navy"><strong>{{$borrowing->bookItem->book->title}}</strong></a>
                                                        </td>
                                                        <td>{{$borrowing->bookItem->book_item_id}}
                                                        </td>
                                                        <td>
                                                            @foreach ($borrowing->bookItem->book->authors as $author)
                                                            <a href="/autorzy/{{$author->id}}"
                                                                class="a-link-navy">{{$author->last_name}},
                                                                {{$author->first_names}}</a>
                                                            {{ $loop->last ? '' : ' •' }}
                                                            @endforeach
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
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changePwdModal" tabindex="-1" role="dialog" aria-labelledby="changePwdModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="changePwdModalForm" action="/zmien-haslo" method="POST">
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
                        <label for="current_password" class="col-md-4 col-form-label control-label text-md-right">Stare
                            hasło</label>
                        <div class="col-md-6">
                            <input type="password" id="current_password" class="form-control" name="current_password"
                                required>
                        </div>
                    </div>
                    <div class="form-group required row">
                        <label for="new_password" class="col-md-4 col-form-label control-label text-md-right">Nowe
                            hasło</label>
                        <div class="col-md-6">
                            <input type="password" id="new_password" class="form-control" name="new_password" required>
                        </div>
                    </div>
                    <div class="form-group required row">
                        <label for="confirm_password"
                            class="col-md-4 col-form-label control-label text-md-right">Powtórz nowe hasło</label>
                        <div class="col-md-6">
                            <input type="password" id="confirm_password" class="form-control" name="confirm_password"
                                required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    <button type="submit" id="change-pwd-btn-submit" class="btn btn-primary">Zmień hasło</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    $('table').each(function() {
    if($(this).find('tr').children("td").length == 0) {
        $(this).hide();
        $( "<h6 class='text-center py-5'>Brak</h6>" ).insertBefore(this);
    }
    });

    //prolong a book
        $(".prolong-book").click(function(e){
        e.preventDefault();
         var confirmed = confirm('Możesz jednorazowo przedłużyć czas na zwrot tej książki o 1 miesiąc. Czy na pewno chcesz to zrobić?');

        if (confirmed) {
        var id = $("input[name=id]", this.form).val();
        $.ajax({
            type:'POST',
            dataType : 'json',
            url:'/prolonguj',
            data: {_token:"{{csrf_token()}}", id: id},
            success:function(data){
                location.reload();
                alert(data.success);
            },
        });
    }
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
            url:'/anuluj-rezerwacje',
            data: {_token:"{{csrf_token()}}", id: id},
            success:function(data){
                location.reload();
                alert(data.success);
            },
        });
    }
    });
</script>
@endsection