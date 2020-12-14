@extends('layouts.admin')

@section('title', 'Rezerwacje')

@section('content')

<div class="container">
    {{-- <div class="row">
        <div class="input-group mb-2 col-sm-12 col-lg-10 mx-auto">
            <input type="text" class="form-control search-phrase" name="search-input" id="search">
            <button type="submit" id="find-book-submit-btn" class="btn btn-primary ml-4 px-lg-4">Szukaj</button>
        </div>
    </div> --}}
    @if($reservations->count() > 0)
    <div class="row mt-3">
        <div class="col-10 mx-auto">
            <table id="dynatable-reserved" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Czytelnik</th>
                        <th>Książka</th>
                        <th>Szczegóły</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="item-table">
                    @foreach ($reservations as $reservation)
                    @if(!isset($reservation->actual_return_date))
                    <tr>
                        <td> <a href="/pracownik/czytelnicy/{{$reservation->user->id}}"
                                class="a-link-navy">{{$reservation->user->first_name.' '.$reservation->user->last_name}}</a>
                        </td>
                        <td> <a href="/pracownik/ksiazki/{{$reservation->bookItem->book->id}}"
                                class="a-link-navy">{{$reservation->bookItem->book->title}}</a>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-light px-3" data-toggle="popover"
                                title="Informacje o rezerwacji" data-placement="bottom"
                                data-content="Egzemplarz: <a href='/pracownik/egzemplarze/{{$reservation->bookItem->id}}' class='a-link-navy'>{{$reservation->bookItem->book_item_id}}</a>
                                <br>
                                Autorzy:<br>
                                @foreach ($reservation->bookItem->book->authors as $author)
                                <a href='/pracownik/autorzy/{{$author->id}}' class='a-link-navy'>
                                {{$author->last_name.', '.$author->first_names}}<br>
                                </a>
                                @endforeach
                                Data rezerwacji: {{date('Y-m-d', strtotime($reservation->created_at))}}
                                <br>
                                Data ważności: {{date('Y-m-d', strtotime($reservation->due_date))}}"><i class="fa fa-info"></i></button>
                        </td>

                        <td>
                            <button type="button" title="Wypożyczenie" class="btn btn-sm btn-primary mb-2"
                                data-toggle="modal"
                                data-target="#borrowBookItemModal-{{$reservation->bookItem->id}}">Wypożyczenie</button>
                        </td>
                        <td>
                            <form>
                                <button type="submit" title="Anuluj rezerwację"
                                    class="btn btn-sm btn-secondary mb-2 cancel-reservation">Anuluj
                                </button>
                                <input type="hidden" name="id" value="{{$reservation->first()->id}}">
                            </form>
                        </td>
                    </tr>
                    @endif
                    <div class="modal fade" id="borrowBookItemModal-{{$reservation->bookItem->id}}" tabindex="-1"
                        role="dialog" aria-labelledby="borrowBookItemModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="/pracownik/egzemplarze/{{$reservation->bookItem->id}}/rezerwacja/wypozycz"
                                    method="POST">
                                    {{ csrf_field() }}
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="borrowBookItemModalLabel">Potwierdź
                                            wypożyczenie
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body pt-0">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-4 col-form-label control-label text-md-right"><strong>Książka:</strong></label>
                                            <label class="col-md-6 col-form-label control-label text-md-left">
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
                                            <label class="col-md-6 col-form-label control-label text-md-left">
                                                {{$reservation->user->first_name}} {{$reservation->user->last_name}}
                                                <br>
                                                PESEL: {{$reservation->user->pesel}}
                                            </label>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-4 col-form-label control-label text-md-right"><strong>Wypożyczenie:</strong></label>
                                            <label class="col-md-6 col-form-label control-label text-md-left">
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
                                        <button type="submit" class="btn btn-primary return-book">Potwierdź</button>
                                        <input type="hidden" name="bookItemId" value="{{$reservation->bookItem->id}}">
                                        <input type="hidden" name="userId" value="{{$reservation->user->id}}">
                                        <input type="hidden" name="reservationId" value="{{$reservation->id}}">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <p class="h6 text-center py-5">Brak rezerwacji</p>
    @endif
</div>

@endsection
@section('script')
<script>

    $(function () {
    $('[data-toggle="popover"]').popover({html:true})
    });

    $("#search").keyup(function () {
        var value = this.value.toLowerCase().trim();

        $("table tr").each(function (index) {
            if (!index) return;
            $(this).find("td:not(:has(button))").each(function () {
                var id = $(this).text().toLowerCase().trim();
                var not_found = (id.indexOf(value) == -1);
                $(this).closest('tr').toggle(!not_found);
                return not_found;
                
            });
        });
    });

    $('#dynatable-reserved').dynatable();

    $("#dynatable-query-search-dynatable-reserved").addClass("form-control-custom");
    // $(".dynatable-search").hide();
    $("tfoot").hide();
    $(".dynatable-per-page-label").css( "margin-right", "8px" );
    $("th").css( "padding", "5px" );

    //cancel a reservation
        $(".cancel-reservation").click(function(e){
        e.preventDefault();
         var confirmed = confirm('Czy na pewno chcesz anulować rezerwację?');

        if (confirmed) {
        var id = $("input[name=id]", this.form).val();
        $.ajax({
            type:'DELETE',
            dataType : 'json',
            url:'/pracownik/rezerwacje/' + id,
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