@extends('layouts.admin')

@section('title', 'Użytkownik '.$user->first_name." ".$user->last_name.' - informacje')


@section('content')

<div class="container col-lg-10">
    <div class="card my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Szczegóły
                <div class="ml-auto">
                    {{-- <form action="/pracownik/autorzy/{{$author->id}}/usun" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" id="delete-publisher-btn-submit" class="btn btn-sm btn-secondary delete"><i
                            class="fa fa-trash-alt"></i></button>
                    <input type="hidden" value="{{$user->id}}" name="id">
                    </form> --}}
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
                <div class="tab-content">
                    <div class="tab-pane fade" id="reservation" role="tabpanel" aria-labelledby="reservation-tab">...
                    </div>
                    <div class="tab-pane fade show active" id="borrowing" role="tabpanel"
                        aria-labelledby="borrowing-tab">
                        {{-- {{$user->borrowings}} --}}

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tytuł książki</th>
                                    <th>Autorzy</th>
                                    <th>Data wypożyczenia</th>
                                    <th>Data zwrotu</th>
                                    <th colspan="2">Akcja</th>
                                </tr>
                            </thead>
                            <tbody class="item-table">
                                @foreach ($user->borrowings as $book)
                                <tr>
                                    <td>{{$book->bookItem->book->title}}
                                    </td>
                                    <td>
                                        @foreach ($book->bookItem->book->authors as $author)
                                        <a href="/pracownik/autorzy/{{$author->id}}"
                                            class="a-link-navy">{{$author->last_name}},
                                            {{$author->first_names}}</a>
                                        {{ $loop->last ? '' : ' •' }}
                                        @endforeach
                                    </td>
                                    <td>{{date('Y-m-d', strtotime($book->borrow_date))}}
                                    </td>
                                    <td>{{date('Y-m-d', strtotime($book->due_date))}}
                                    </td>
                                    <td>@if(!$book->was_prolonged)
                                        <form>
                                            <button type="submit" title="Prolonguj"
                                                class="btn btn-sm btn-light prolong-item">Prolonguj</button>
                                            <input type="hidden" name="id" value="{{$book->id}}">
                                        </form>
                                        @endif
                                    </td>
                                    <td>
                                        <form>
                                            <button type="submit" title="Zwrot"
                                                class="btn btn-sm btn-primary return-item">Zwrot</button>
                                            <input type="hidden" name="id" value="{{$book->id}}">
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">historia...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
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