@extends('layouts.admin')

@section('title', 'Wydawnictwo '.$publisher->name.' - informacje')


@section('content')

<div class="container col-lg-10">
    <div class="card my-1">
        <div class="h5 card-header">
            <div class="row px-2">
                Szczegóły
                <div class="ml-auto">
                    <form action="/pracownik/wydawnictwa/{{$publisher->id}}/usun" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" id="delete-publisher-btn-submit"
                            class="btn btn-sm btn-secondary delete"><i class="fa fa-trash-alt"></i></button>
                        <input type="hidden" value="{{$publisher->id}}" name="id">
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class=card-text">
                <ul class="list-unstyled">
                    <li><strong>Nazwa: </strong><a class="editable-input" id="name">{{$publisher->name}}<i
                                class="fa fa-pencil-alt ml-2"></i></a>
                    </li>
                    <br>
                    <li><strong>Książki: </strong> {{$publisher->books->count()}}
                        <ul class="list-group mt-2">
                            @foreach ($publisher->books as $book)
                            <li class="list-group-item"><a href="/pracownik/ksiazki/{{$book->id}}"
                                    class="a-link-navy">{{$book->title}}</a>
                            </li>
                            @endforeach

                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
<script>
    $.fn.editable.defaults.mode = 'inline';
    var id = {!! json_encode($publisher->id) !!};

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
                url: '/pracownik/wydawnictwa/'+ id + '/edycja',
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

//     //delete publisher
// $("#delete-publisher-btn-submit").click(function(e){
//       e.preventDefault();
//       $.ajax({
//          type:'POST',
//          dataType : 'json',
//          url:'/pracownik/wydawnictwa/'+id+'/usun',
//          data: {_token:"{{csrf_token()}}", id: id},
//         //  success:function(data){
//         //      console.log(data);
//         //     alert("bb");
//         //  },
//         //  error: function(data){
//         //      console.log(data.error);
//         //     alert("aa");
//         //   }
//     });
//   });
// 
</script>

@endsection