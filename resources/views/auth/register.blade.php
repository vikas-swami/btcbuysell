@extends('front.layout.master')
@section('body')
    <form class="form-signin" method="post" action="{{url('/register')}}">
        <div class="jumbotron">
        @csrf
        <h1 class="h3 mb-3 font-weight-normal">Register got your account with {{$general->sitename}} is free,quick and easy.</h1>

        @if (count($errors) > 0)
            <div class="row">
                <div class="col-md-010">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h12><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Alert!</h12>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group">
            <label for="inputEmail" class="sr-only">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder="Enter Full Name" required>
        </div>

        <div class="form-group">
            <label for="inputEmail" class="sr-only">E-mail</label>
            <input type="email" name="email" class="form-control" value="{{old('email')}}" placeholder="Enter your E-mail" required>
        </div>

        <div class="form-group">
            <label for="inputEmail" class="sr-only">Username</label>
            <input type="text" name="username" class="form-control" value="{{old('username')}}" placeholder="Enter Username" required>
        </div>

        <div class="form-group">
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Password" required>
        </div>

        <div class="form-group">
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" class="form-control" name="password_confirmation" placeholder="Re-Enter Password"  required>
        </div>



        <button class="btn btn-lg btn-primary btn-block" type="submit">SIGN UP</button>


        <p class="mt-5 mb-3 text-muted">{{$general->copyright}}</p>
        </div>
    </form>
@endsection
