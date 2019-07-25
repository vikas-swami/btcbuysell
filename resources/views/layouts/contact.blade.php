@extends('front.layout.master')
@section('style')
    <style>
        iframe{
            width:100% ;
            height: 100%
        }
    </style>
@stop
@section('body')
<div class="row">

    <div class="col-md-6 col-sm-12">

        <div class="jumbotron text-center">
    
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><i class="fa fa-map-marker"></i> {{$general->address}}</li>
                <li class="list-group-item"><i class="fa fa-phone"></i> {{$general->phone}}</li>
                <li class="list-group-item"><i class="fa fa-envelope-o"></i> {{$general->email}}</li>
            </ul>
        </div>

        <div class="jumbotron text-center" style="padding: 10px;height: 276px;">
            {!! $basic->google_map !!}
        </div>

    </div>

    <div class="col-md-6 col-sm-12">
        <div class="jumbotron">
            <h2 class="text-center">CONTACT US</h2>
            <form class="form-horizontal" action="{{ route('contact-submit') }}" method="post">
                @csrf
                <div class="form-group">
                    <label class="control-label" for="email">Your Name</label>

                        <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif

                </div>


                <div class="form-group">
                    <label class="control-label" for="email">Email Address</label>

                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif

                </div>

                <div class="form-group">
                    <label class="control-label" for="email">Message</label>

                        <textarea rows="5" class="form-control" name="message" placeholder="Your Message..."></textarea>
                        @if ($errors->has('message'))
                            <span class="help-block">
                                <strong>{{ $errors->first('message') }}</strong>
                            </span>
                        @endif
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@stop