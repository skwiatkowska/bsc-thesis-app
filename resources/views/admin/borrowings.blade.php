@extends('layouts.admin')

@section('title', 'Wypożyczenia')

@section('content')

<div class="container">
    <div class="row">
            <div class="input-group mb-2 col-sm-12 col-lg-10 mx-auto">
                <input type="text" class="form-control search-phrase" name="search-input" id="search">
                <button type="submit" id="find-book-submit-btn" class="btn btn-primary ml-4 px-lg-4">Szukaj</button>
            </div>
    </div>
    
    @if (($borrowings->count() > 0))
    <div class="row mt-5">
        <div class="col-10 mx-auto">
            <table id="dynatable-borrow" class="table table-striped table-bordered mt-1">
                <thead>
                    <tr>
                        <th>Czytelnik</th>
                        <th>Książka</th>
                        <th>Egzemplarz</th>
                        <th>Autorzy</th>
                        <th>Wypożyczono</th>
                        <th>Data zwrotu</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="item-table">
                    @foreach ($borrowings as $borrowing)
                    @if(!isset($borrowing->actual_return_date))
                    <tr>
                        <td> <a href="/pracownik/czytelnicy/{{$borrowing->user->id}}"
                                class="a-link-navy"><strong>{{$borrowing->user->first_name.' '.$borrowing->user->last_name}}</strong></a>
                        </td>
                        <td> <a href="/pracownik/ksiazki/{{$borrowing->bookItem->book->id}}"
                                class="a-link-navy"><strong>{{$borrowing->bookItem->book->title}}</strong></a>
                        </td>
                        <td>
                            <a href="/pracownik/egzemplarze/{{$borrowing->bookItem->id}}"
                                class="a-link-navy">{{$borrowing->bookItem->book_item_id}}</a>
                        </td>
                        <td>
                            @foreach ($borrowing->bookItem->book->authors as $author)
                            <a href="/pracownik/autorzy/{{$author->id}}" class="a-link-navy">
                                {{$author->last_name.', '.$author->first_names}}
                            </a>
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
                            data-toggle="modal" data-target="#returnBookItemModal-{{$borrowing->bookItem->id}}">Zwrot</button>
                            @endif
                        </td>
                    </tr>
                    @endif
                    <div class="modal fade" id="returnBookItemModal-{{$borrowing->bookItem->id}}"
                        tabindex="-1" role="dialog" aria-labelledby="returnBookItemModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form
                                    action="/pracownik/egzemplarze/{{$borrowing->bookItem->id}}/zwroc"
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
                                                {{$borrowing->user->first_name}} {{$borrowing->user->last_name}}
                                                <br>
                                                PESEL: {{$borrowing->user->pesel}}
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
    </div>
    @else
    <p class="h6 text-center py-5">Brak wypożyczeń</p>
    @endif
</div>

@endsection
@section('script')
<script>
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

    $('#dynatable-borrow').dynatable();

    $(".dynatable-search").hide();
    $("tfoot").hide();
    $(".dynatable-per-page-label").css( "margin-right", "8px" );
    $("th").css( "padding", "5px" );


       //prolong a book
       $(".prolong-book").click(function(e){
        e.preventDefault();

        var confirmed = confirm('Możesz jednorazowo przedłużyć czas na zwrot tej książki o 1 miesiąc. Czy na pewno chcesz to zrobić?');

        if (confirmed) {
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
            // error: function(data){
            //     alert(data.responseJSON.error);
            // }
        });
    }
    });
</script>

@endsection