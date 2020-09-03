@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html>
<head>
    <title>Practical Assignment</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <h2>Ajax Employee</h2>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                    <form id="employee" method="post" action="javascript:void(0)">
                        <div class="alert alert-success d-none" id="msg_div">   
                        <span id="res_message"></span>
                        </div>
                        
                    <div class="form-group row add">
                        <div class="col-md-8">
                            <label for="empname" class="col-md-4 col-form-label text-md-right">Employee Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter some name" required>
                        </div>
                        <div class="col-md-8">
                            <label for="empadd" class="col-md-4 col-form-label text-md-left">Employee Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Enter some Address" required>
                        </div>

                        <div class="col-md-8">
                            <label for="empcontact" class="col-md-4 col-form-label text-md-right">Employee Contact NO</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter some Contact NO" required>
                        </div>

                        <!-- <div class="col-md-8">
                            <label for="empimg" class="col-md-4 col-form-label text-md-right">Employee Photo</label>
                            <input type="file" class="form-control" id="empimg" name="empimg" placeholder="Enter some Photo" required>
                        </div> -->

                        <div class="col-md-8">
                            <button class="btn btn-success" type="submit" id="send_form">ADD Employee</button>
                        </div>
                   </form>

                    <script>
                      $(document).ready(function(){
                            $('#send_form').click(function(e){
                                e.preventDefault();

                            });
                            });

                    $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    $.ajax({
                        url:"{{url('employee.create')}}",
                        method:'post',
                        data:$('employee').serialize(),
                        success: function(response){
                            $('#send_form').html('Submit');
                            $('#res_message').show();
                            $('#res_message').html(response.msg);
                            $('#msg_div').removeClass('d-none');
                            document.getElementById("employee").reset(); 
                            setTimeout(function(){
                            $('#res_message').hide();
                            $('#msg_div').hide();
                            },10000);
                        }});
                      });
                });                   
                    </script>
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Mobile NO</th>
                                <th width="300px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
              </div>
              </div>
            </div>
        </div>
    </div>
</div>

@endsection
