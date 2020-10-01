@extends('layouts.layout')

@section('title', 'Załóż nowe konto')

@section('content')

{{-- source: https://bbbootstrap.com/snippets/multi-step-form-wizard-30467045 --}}
<div class="container">
    <!-- MultiStep Form -->
    <div class="container-fluid" id="grad1">
        <div class="row justify-content-center mt-0">
            <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0 mt-3 mb-2">
                <div class="card  card-borderless px-0 pt-4 pb-0 mt-3 mb-3">
                    <h2><strong>Załóż nowe konto Czytelnika</strong></h2>
                    <div class="row">
                        <div class="col-md-12 mx-0">
                            <form id="msform">
                                <!-- progressbar -->
                                <ul id="progressbar">
                                    <li class="active" id="account"><strong>Konto</strong></li>
                                    <li id="personal"><strong>Dane osobowe</strong></li>
                                    <li id="address"><strong>Adres</strong></li>
                                    <li id="confirm"><strong>Koniec</strong></li>
                                </ul> <!-- fieldsets -->
                                <fieldset>
                                    <div class="form-card">
                                        <h2 class="fs-title">Informacje o koncie</h2>
                                        <input class="mt-4" type="email" name="email" placeholder="Adres e-mail"
                                            required />
                                        <input type="password" name="password" placeholder="Hasło" required />
                                        <input type="password" name="password_confirmation" placeholder="Powtórz hasło"
                                            required />
                                    </div>
                                    <input type="button" name="next" class="next action-button" value="Dalej" />
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <h2 class="fs-title">Informacje osobiste</h2>

                                        <input type="text" name="fname" placeholder="Imię" required />
                                        <input type="text" name="lname" placeholder="Nazwisko" required />
                                        <input type="text" name="pesel" placeholder="PESEL" required />
                                        <input type="text" name="phone" placeholder="Numer telefonu" required />
                                    </div>
                                    <input type="button" name="previous" class="previous action-button-previous"
                                        value="Cofnij" />
                                    <input type="button" name="next" class="next action-button" value="Dalej" />

                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <h2 class="fs-title">Adres</h2>
                                        <input class="mt-4" type="text" name="street" placeholder="Ulica i numer domu"
                                            required />
                                        <input type="text" name="zipcode" placeholder="Kod pocztowy" required />
                                        <input type="text" name="city" placeholder="Miasto"
                                            required />
                                    </div>
                                    <input type="button" name="previous" class="previous action-button-previous"
                                        value="Cofnij" />
                                    <input type="button" name="next" class="next action-button" id="register-submit-btn"
                                        value="Zarejestruj" />
                                </fieldset>


                                <fieldset>
                                    <div class="form-card">
                                        <h2 class="fs-title text-center">Twoje konto zostało utworzone</h2> <br><br>
                                        <div class="row justify-content-center">
                                            <div class="col-3"> <img
                                                    src="https://img.icons8.com/color/96/000000/ok--v2.png"
                                                    class="fit-image"> </div>
                                        </div> <br><br>
                                        <div class="row justify-content-center">
                                            <div class="text-center">
                                                <h5><a class="pt-3" href="logowanie"><strong>Kliknij tutaj i zaloguj
                                                            się</strong></a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //form submitting
  $("#register-submit-btn").click(function(e){
      e.preventDefault();
      var fname = $("input[name=fname]").val();
      var lname = $("input[name=lname]").val();
      var password = $("input[name=password]").val();
      var password_confirmation = $("input[name=password_confirmation]").val();
      var email = $("input[name=email]").val();
      var pesel = $("input[name=pesel]").val();
      var phone = $("input[name=phone]").val();
      var street = $("input[name=street]").val();
      var zipcode = $("input[name=zipcode]").val();
      var city = $("input[name=city]").val();
     
    
      $.ajax({
         type:'POST',
         dataType : 'json',
         url:'/rejestracja',
         data: {_token:"{{csrf_token()}}", fname: fname, lname: lname, password:password, password_confirmation: password_confirmation, email:email, pesel:pesel, phone:phone, street: street, zipcode: zipcode, city: city},
         success:function(data){
            location.reload();
            alert(data.success);
         },
         error: function(data){
            alert(data.responseJSON.error);
          }
}     );

  });
</script>

@endsection