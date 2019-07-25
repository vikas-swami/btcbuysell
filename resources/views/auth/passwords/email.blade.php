@extends('front.layout.master')
@section('style')

@stop
@section('body')
    <form class="form-signin" method="post" action="{{ route('user.password.email') }}">
        <div class="jumbotron">
            @csrf
            <h1 class="h3 mb-3 font-weight-normal">Forgot Password</h1>

            <div class="form-group">
                <label for="inputEmail" class="sr-only">Username</label>
                <input class="form-control"  type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                @endif
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Forgot Password</button>


            <p class="mt-5 mb-3 text-muted text-center">{{$general->copyright}}</p>
        </div>
    </form>

@endsection
