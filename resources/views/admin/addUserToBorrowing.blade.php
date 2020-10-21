@extends('layouts.admin')

@section('title', 'Wypożyczanie: '.$item->book->title.', egzemplarz: '.$item->bookitem_id)

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-1">
            <a href="/pracownik/ksiazki/{{$item->book->id}}" type="button"
                class="btn btn-sm btn-secondary btn-rounded"><i class="fa fa-arrow-left"></i> Powrót
            </a>

        </div>


        <div class="progressbar-wrapper mb-5 col-sm-11 ml-0">
            <ul class="progressbar">
                <li class="active">Wybór egzemplarza</li>
                <li class="active">Wybór czytelnika</li>
                <li>Gotowe!</li>
            </ul>
        </div>
    </div>
    {{-- {{$item}} --}}
    <div class="row">
        <form class="form-inline col-12 justify-content-center"
            action="/pracownik/ksiazki/egzemplarze/{{$item->id}}/wypozycz" method="POST">
            {{ csrf_field() }}
            <div class="input-group mb-2 col-sm-12 col-lg-3 px-1">
                <div class="input-group-prepend">
                    <div class="input-group-text">Szukaj w:</div>
                </div>
                <select class="form-control search-in-select" name="searchIn">
                    <option value="pesel">PESEL</option>
                    <option value="lname">Nazwisko</option>
                </select>
            </div>
            <div class="input-group col-sm-12 col-lg-4 mb-2 px-1">
                <div class="input-group-prepend">
                    <div class="input-group-text">Fraza:</div>
                </div>
                <input type="text" class="form-control search-phrase" name="phrase" id="search-phrase-input">

            </div>
            <div class="input-group col-lg-2 mb-2">
                <button type="submit" id="find-book-submit-btn" class="btn btn-primary ml-4 px-lg-4">Znajdź
                    Czytelnika</button>

            </div>
            <div class="input-group mb-2 col-lg-2 ml-auto">
                <button type="button" class="btn btn-secondary btn-rounded" data-toggle="modal"
                    data-target="#newUserModal">Nowy
                    Czytelnik</button>
            </div>

        </form>
    </div>
    @if (!empty($phrase))
    <div class="row mt-2">
        <p class="h6 text-center searchingInfo mx-auto">Aktualne wyszukiwanie: <strong>{{$phrase}}</strong></p>
    </div>
    @if (!empty($users) && $users->count() > 0)
    <div class="row mt-5">
        <div class="col-10 mx-auto">
            <table class="table table-striped table-bordered text-center mt-1">
                <thead>
                    <tr>
                        <th>Imię i nazwisko</th>
                        <th>PESEL</th>
                        <th>Akcja</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>
                            <a href="/pracownik/czytelnicy/{{$user->id}}" target="_blank"><span
                                    class="a-link-navy">{{$user->first_name}} {{$user->last_name}}</span></a>
                        </td>
                        <td>{{$user->pesel}}
                        </td>
                        <td><button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                data-target="#newBookingModal">Wybierz
                            </button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <p class="h6 text-center py-5">Nie znaleziono</p>
    @endif
    @endif

</div>
<div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="newUserModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="newUserForm">
                {{-- {{ csrf_field() }} --}}
                <div class="modal-header">
                    <h5 class="modal-title" id="newUserModalLabel">Nowe Czytelnik
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row required">
                        <label for="fname" class="col-md-4 col-form-label control-label text-md-right">Imię</label>
                        <div class="col-md-6">
                            <input type="text" id="fname" class="form-control" name="fname" required>
                        </div>
                    </div>
                    <div class="form-group row required">
                        <label for="lname" class="col-md-4 col-form-label control-label text-md-right">Nazwisko</label>
                        <div class="col-md-6">
                            <input type="text" id="lname" class="form-control" name="lname" required>
                        </div>
                    </div>

                    <div class="form-group row required">
                        <label for="pesel" class="col-md-4 col-form-label control-label text-md-right">PESEL</label>
                        <div class="col-md-6">
                            <input type="text" id="pesel" class="form-control" name="pesel" required>
                        </div>
                    </div>

                    <div class="form-group row required">
                        <label for="email" class="col-md-4 col-form-label control-label text-md-right">E-Mail</label>
                        <div class="col-md-6">
                            <input type="text" id="email" class="form-control" name="email" required>
                        </div>
                    </div>

                    <div class="form-group row required">
                        <label for="phone" class="col-md-4 col-form-label control-label text-md-right">Numer
                            telefonu</label>
                        <div class="col-md-6">
                            <input type="text" id="phone" name="phone" class="form-control" required>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="isModal" value="true">

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    <button type="submit" id="new-user-btn-submit" class="btn btn-primary">Dodaj</button>
                </div>
            </form>
        </div>
    </div>
</div>
@if(!empty($users) && $users->count() > 0)
<div class="modal fade" id="newBookingModal" tabindex="-1" role="dialog" aria-labelledby="newBookingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="newBookingConfirmForm" action="wypozycz/zapisz" method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="newBookingModalLabel">Potwierdź wypożyczenie
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label control-label text-md-right"><strong>Książka:</strong></label>
                        <label class="col-md-6 col-form-label control-label text-md-left">
                            "<i>{{$item->book->title}}</i> "
                            <br>
                            @foreach ($book->authors as $author)
                            {{$author->last_name}}, {{$author->first_names}}
                            {{ $loop->last ? '' : ' <br>' }}
                            @endforeach
                        </label>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label control-label text-md-right"><strong>Czytelnik:</strong></label>
                        <label class="col-md-6 col-form-label control-label text-md-left">
                            {{$user->first_name}} {{$user->last_name}}
                            <br>
                            PESEL: {{$user->pesel}}
                        </label>
                    </div>  
                </div>
            <input type="hidden" name="bookItemId" value="{{$item->id}}">
            <input type="hidden" name="userId" value="{{$user->id}}">

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    <button type="submit" id="confirm-booking-btn-submit" class="btn btn-primary">Potwierdź</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
<script>
    $("#new-user-btn-submit").click(function(e){
      e.preventDefault();
      var fname = $("input[name=fname]").val();
      var lname = $("input[name=lname]").val();
      var pesel = $("input[name=pesel]").val();
      var email = $("input[name=email]").val();
      var phone = $("input[name=phone]").val();
      var isModal = $("input[name=isModal]").val();
      $.ajax({
         type:'POST',
         dataType : 'json',
         url:'/pracownik/czytelnicy/nowy',
         data: {_token:"{{csrf_token()}}", fname: fname, lname: lname, pesel:pesel, email:email, phone:phone, isModal:isModal},
         success:function(data){
            console.log(data);
            location.reload();
            alert(data.success);
         },
         error: function(data){
             console.log(data);
          }
    });

  });
</script>

@endsection