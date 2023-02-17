@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form id="createForm">
                            <div class="row mb-2">
                                <div id="discountButton" class="text-center">
                                    <button type="submit" class="btn btn-primary">
                                        Получить скидку!
                                    </button>
                                </div>
                                <div hidden id=discountFields class="col-6 m-auto">
                                    <div id="discountValue" class="alert alert-success" role="alert">
                                    </div>
                                    <div id="discountCode" class="alert alert-success" role="alert">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Проверка скидки!</div>
                    <div class="card-body">
                        <form id="checkForm">
                            <div class="row mb-2">
                                <div class="col-md-4 text-md-end">
                                    <button type="submit" class="btn btn-primary">
                                        Проверить скидку
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <input id="checkDiscount" class="form-control">
                                </div>
                                <div class="col-6 mt-2 m-auto">
                                    <div hidden id="checkSuccess" class="alert alert-success" role="alert">
                                    </div>
                                    <div hidden id="checkFail" class="alert alert-danger" role="alert">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

    <script>
    $('#createForm').on('submit',function(event){
        event.preventDefault();

        $.ajax({
          url: "/discount/create",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
          },
          success:function(response){
            console.log(response)
           $('#discountFields').removeAttr('hidden')
           $('#discountButton').attr('hidden', 'true')
           $('#discountValue').text('Размер вашей скидки - ' + response.value + '%')
           $('#discountCode').text('Код скидки - ' + response.code)
          },
         });
        });
      </script>

    <script>
    $('#checkForm').on('submit',function(event){
        event.preventDefault();
        let code = $('#checkDiscount').val()
        $.ajax({
          url: "/discount/check",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
             code:code,
          },
          success:function(response){
          console.log(response)
            if (response == true){
               $('#checkSuccess').removeAttr('hidden')
               $('#checkFail').attr('hidden', 'true')
               $('#checkSuccess').text('Код скидки активен')
             }else {
               $('#checkFail').removeAttr('hidden')
               $('#checkSuccess').attr('hidden', 'true')
               $('#checkFail').text('Код скидки не активен')
             }
          },
         });
        });
      </script>
@endsection
