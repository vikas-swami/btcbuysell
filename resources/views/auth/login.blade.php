@extends('front.layout.master')
@section('style')

@stop
@section('body')

        <form class="form-signin" method="post" action="{{ route('login') }}">
            <div class="jumbotron">
                @csrf
                <h1 class="h3 mb-3 font-weight-normal">Please login to access {{$general->sitename}} area.</h1>

               <div class="form-group">
                   <label for="inputEmail" class="sr-only">Username</label>
                   <input type="text" id="inputEmail" class="form-control" name="username" value="{{old('username')}}" placeholder="Username" required autofocus>
                   @if ($errors->has('username'))
                       <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                   @endif
               </div>

                <div class="form-group">
                    <label for="inputPassword" class="sr-only">Password</label>
                    <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Password" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
               </div>



                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

                <a href="{{ route('password.request') }}">Forgot password?</a>

                <p class="mt-5 mb-3 text-muted">{{$general->copyright}}</p>
            </div>
        </form>

@endsection
