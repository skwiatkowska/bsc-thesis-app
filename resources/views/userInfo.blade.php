@extends('layouts.layout')

@section('title', 'Moje dane')


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
                    <li><strong>Data utworzenia konta: </strong>{{$user->created_at}}</li>


                </ul>
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