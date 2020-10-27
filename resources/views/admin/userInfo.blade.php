@extends('layouts.admin')

@section('title', 'Użytkownik '.$user->first_name." ".$user->last_name.' - informacje')


@section('content')

<div class="container col-lg-10">
    <div class="card my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Szczegóły
                <div class="ml-auto">
                    <form action="/pracownik/czytelnicy/{{$user->id}}/usun" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" id="delete-publisher-btn-submit"
                            class="btn btn-sm btn-secondary delete"><i class="fa fa-trash-alt"></i></button>
                        <input type="hidden" value="{{$user->id}}" name="id">
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class=card-text">
                <ul class="list-unstyled">
                    <li><strong>Imię: </strong><a class="editable-input" id="fname">{{$user->first_name}}<i
                                class="fa fa-pencil-alt ml-2"></i></a></li>
                    <li><strong>Nazwisko: </strong><a class="editable-input" id="lname">{{$user->last_name}}<i
                                class="fa fa-pencil-alt ml-2"></i></a></li>
                    <li><strong>PESEL: </strong><a class="editable-input" id="pesel">{{$user->pesel}}<i
                                class="fa fa-pencil-alt ml-2"></i></a></li>
                    <li><strong>Telefon: </strong><a class="editable-input" id="phone">{{$user->phone}}<i
                                class="fa fa-pencil-alt ml-2"></i></a></li>
                    <li><strong>E-mail: </strong><a class="editable-input" id="email">{{$user->email}}<i
                                class="fa fa-pencil-alt ml-2"></i></a></li>
                    <li><strong>Ulica: </strong><a class="editable-input" id="street">{{$user->street}}<i
                                class="fa fa-pencil-alt ml-2"></i></a></li>
                    <li><strong>Numer domu: </strong><a class="editable-input"
                            id="house_number">{{$user->house_number}}<i class="fa fa-pencil-alt ml-2"></i></a></li>
                    <li><strong>Kod pocztowy: </strong><a class="editable-input" id="zipcode">{{$user->zipcode}}<i
                                class="fa fa-pencil-alt ml-2"></i></a></li>
                    <li><strong>Miasto: </strong><a class="editable-input" id="city">{{$user->city}}<i
                                class="fa fa-pencil-alt ml-2"></i></a></li>
                    <li><strong>Data utworzenia konta: </strong>{{date('Y-m-d', strtotime($user->created_at))}}</li>

                </ul>
            </div>
        </div>
    </div>
    <div class="card my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Rezerwacje i wypożyczenia
                <div class="ml-auto">
                </div>
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
                    <div class="tab-pane fade" id="reservation" role="tabpanel" aria-labelledby="reservation-tab">
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
                                    <td> <a href="/pracownik/ksiazki/{{$reservation->bookItem->book->id}}"
                                            class="a-link-navy"><strong>{{$reservation->bookItem->book->title}}</strong></a>
                                    </td>
                                    <td>
                                        <a href="/pracownik/egzemplarze/{{$reservation->bookItem->id}}"
                                            class="a-link-navy">{{$reservation->bookItem->book_item_id}}</a>
                                    </td>
                                    <td>
                                        @foreach ($reservation->bookItem->book->authors as $author)
                                        <a href="/pracownik/autorzy/{{$author->id}}" class="a-link-navy">
                                            {{$author->last_name.', '.$author->first_names}}
                                        </a>
                                        @endforeach
                                    </td>
                                    <td>{{date('Y-m-d', strtotime($reservation->reservation_date))}}
                                    </td>
                                    <td>{{date('Y-m-d', strtotime($reservation->due_date))}}
                                    </td>


                                    <td>
                                        <button type="button" title="Wypożyczenie" class="btn btn-sm btn-primary mb-2"
                                            data-toggle="modal"
                                            data-target="#borrowBookItemModal-{{$reservation->bookItem->id}}">Wypożyczenie</button>
                                    </td>
                                </tr>
                                @endif
                                <div class="modal fade" id="borrowBookItemModal-{{$reservation->bookItem->id}}"
                                    tabindex="-1" role="dialog" aria-labelledby="borrowBookItemModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form
                                                action="/pracownik/egzemplarze/{{$reservation->bookItem->id}}/rezerwacja/wypozycz"
                                                method="POST">
                                                {{ csrf_field() }}
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="borrowBookItemModalLabel">Potwierdź
                                                        wypożyczenie
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    </button>
                                                </div>
                                                <div class="modal-body pt-0">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-md-4 col-form-label control-label text-md-right"><strong>Książka:</strong></label>
                                                        <label
                                                            class="col-md-6 col-form-label control-label text-md-left">
                                                            "{{$reservation->bookItem->book->title}}" , egzemplarz:
                                                            {{$reservation->bookItem->book_item_id}}
                                                            <br>
                                                            @foreach ($reservation->bookItem->book->authors as $author)
                                                            {{$author->last_name}}, {{$author->first_names}}
                                                            @if(!$loop->last)
                                                            <br>
                                                            @endif
                                                            @endforeach
                                                            <br>

                                                        </label>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-md-4 col-form-label control-label text-md-right"><strong>Czytelnik:</strong></label>
                                                        <label
                                                            class="col-md-6 col-form-label control-label text-md-left">
                                                            {{$reservation->user->first_name}}
                                                            {{$reservation->user->last_name}}
                                                            <br>
                                                            PESEL: {{$reservation->user->pesel}}
                                                        </label>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-md-4 col-form-label control-label text-md-right"><strong>Wypożyczenie:</strong></label>
                                                        <label
                                                            class="col-md-6 col-form-label control-label text-md-left">
                                                            Data wypożyczenia: <br>
                                                            {{date('Y-m-d')}}
                                                            <br>
                                                            Oczekiwana data zwrotu: <br>

                                                            {{date('Y-m-d', strtotime( "+1 month"))}}
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="modal-footer p-3">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Zamknij</button>
                                                    <button type="submit"
                                                        class="btn btn-primary return-book">Potwierdź</button>
                                                    <input type="hidden" name="bookItemId"
                                                        value="{{$reservation->bookItem->id}}">
                                                    <input type="hidden" name="userId"
                                                        value="{{$reservation->user->id}}">
                                                    <input type="hidden" name="reservationId"
                                                        value="{{$reservation->id}}">

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade show active" id="borrowing" role="tabpanel"
                        aria-labelledby="borrowing-tab">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Tytuł książki</th>
                                    <th>Autorzy</th>
                                    <th>Wypożyczono</th>
                                    <th>Zwrot</th>
                                    <th colspan="2">Akcja</th>
                                </tr>
                            </thead>
                            <tbody class="item-table">
                                @foreach ($user->borrowings as $borrowing)
                                @if(!isset($borrowing->actual_return_date))
                                <tr>
                                    <td> <a href="/pracownik/ksiazki/{{$borrowing->bookItem->book->id}}"
                                            class="a-link-navy"><strong>{{$borrowing->bookItem->book->title}}</strong></a>
                                        <a href="/pracownik/egzemplarze/{{$borrowing->bookItem->id}}"
                                            class="a-link-navy">egzemplarz {{$borrowing->bookItem->book_item_id}}</a>
                                    </td>
                                    <td>
                                        @foreach ($borrowing->bookItem->book->authors as $author)
                                        <a href="/pracownik/autorzy/{{$author->id}}"
                                            class="a-link-navy">{{$author->last_name}},
                                            {{$author->first_names}}</a>
                                        @endforeach
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
                                            data-toggle="modal"
                                            data-target="#returnBookItemModal-{{$borrowing->bookItem->id}}">Zwrot</button>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                <div class="modal fade" id="returnBookItemModal-{{$borrowing->bookItem->id}}"
                                    tabindex="-1" role="dialog" aria-labelledby="returnBookItemModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="/pracownik/egzemplarze/{{$borrowing->bookItem->id}}/zwroc"
                                                method="POST">
                                                {{ csrf_field() }}<div class="modal-header">
                                                    <h5 class="modal-title" id="returnBookItemModalLabel">Potwierdź
                                                        zwrot
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    </button>
                                                </div>
                                                <div class="modal-body pt-0">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-md-4 col-form-label control-label text-md-right"><strong>Książka:</strong></label>
                                                        <label
                                                            class="col-md-6 col-form-label control-label text-md-left">
                                                            "{{$borrowing->bookItem->book->title}}" , egzemplarz:
                                                            {{$borrowing->bookItem->book_item_id}}
                                                            <br>
                                                            @foreach ($borrowing->bookItem->book->authors as $author)
                                                            {{$author->last_name}}, {{$author->first_names}}
                                                            @if(!$loop->last)
                                                            <br>
                                                            @endif
                                                            @endforeach
                                                            <br>

                                                        </label>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-md-4 col-form-label control-label text-md-right"><strong>Czytelnik:</strong></label>
                                                        <label
                                                            class="col-md-6 col-form-label control-label text-md-left">
                                                            {{$user->first_name}} {{$user->last_name}}
                                                            <br>
                                                            PESEL: {{$user->pesel}}
                                                        </label>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-md-4 col-form-label control-label text-md-right"><strong>Wypożyczenie:</strong></label>
                                                        <label
                                                            class="col-md-6 col-form-label control-label text-md-left">
                                                            Data wypożyczenia: <br>
                                                            {{date('Y-m-d', strtotime($borrowing->borrow_date))}}
                                                            <br>
                                                            Oczekiwana data zwrotu: <br>

                                                            {{date('Y-m-d', strtotime($borrowing->due_date))}}
                                                            <br>
                                                            Opłata:
                                                            @if(new \DateTime($borrowing->due_date)< new \DateTime())
                                                                <strong>
                                                                {{ (int)date_diff(new \DateTime($borrowing->due_date), new \DateTime())->format("%d")*0.5}}</strong>
                                                                @else
                                                                -
                                                                @endif
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="modal-footer p-3">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Zamknij</button>
                                                    <button type="submit"
                                                        class="btn btn-primary return-book">Potwierdź</button>
                                                    <input type="hidden" name="id" value="{{$borrowing->bookItem->id}}">

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Tytuł książki</th>
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
                                    <td> <a href="/pracownik/ksiazki/{{$borrowing->bookItem->book->id}}"
                                            class="a-link-navy"><strong>{{$borrowing->bookItem->book->title}}</strong></a>
                                        <a href="/pracownik/egzemplarze/{{$borrowing->bookItem->id}}"
                                            class="a-link-navy">egzemplarz {{$borrowing->bookItem->book_item_id}}</a>
                                    </td>
                                    <td>
                                        @foreach ($borrowing->bookItem->book->authors as $author)
                                        <a href="/pracownik/autorzy/{{$author->id}}"
                                            class="a-link-navy">{{$author->last_name}},
                                            {{$author->first_names}}</a>
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


    $.fn.editable.defaults.mode = 'inline';
    var id = {!! json_encode($user->id) !!};

    $(document).ready(
        function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.editable-input').editable({
                validate: function(value) {
                    if($.trim(value) == '')
                        return 'Podaj wartość';
                },
                type: 'text',
                placement: 'right',
                send:'always',
                pk: id,
                url: '/pracownik/czytelnicy/'+ id + '/edycja',
                ajaxOptions: {
                    dataType: 'json',
                    type: 'post'
                },
                success:function(data){
            location.reload();
            alert(data.success);
         },
            });
        }
    );
</script>

@endsection