@extends('front.layout.master')
@section('style')

@stop
@section('body')
    <div class="row padding-pranto-top">
        @foreach($balance as $gate)
        <div class="col-md-6 text-center margin-top-pranto">
            <div class="card border-dark">
                <div class="card-header">{{$gate->gateway->name}} Balance :</div>
                <div class="card-body text-dark">
                    <h6 class="card-title">@if($gate->balance == 0) 0.0000 {{$gate->gateway->currency}} @else {{round($gate->balance, 8)}} {{$gate->gateway->currency}}@endif</h6>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row padding-pranto-top margin-top-pranto padding-pranto-bottom">
        <div class="col-md-3 text-center margin-top-pranto">
            <div class="card border-dark">
                <div class="card-header">
                    <h5>Open Advertisements :</h5>
                </div>
                <div class="card-body text-dark">
                    <h5 class="card-title">{{App\AdvertiseDeal::where('from_user_id', Auth::id())->where('status', 0)->count()}}</h5>
                </div>
                <div class="card-footer">
                    <a href="{{route('open.trade')}}" class="btn btn-primary btn-block">Open Trades</a>
                </div>
            </div>
        </div>


        <div class="col-md-3 text-center margin-top-pranto">
            <div class="card border-dark">
                <div class="card-header">
                    <h5>All closed trades :</h5>
                </div>
                <div class="card-body text-dark">
                    <h5 class="card-title">{{App\AdvertiseDeal::where('from_user_id', Auth::id())->where('status', 2)->count()}}</h5>
                </div>
                <div class="card-footer">
                    <a href="{{route('close.trade')}}" class="btn btn-primary btn-block">Closed Trades</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 text-center margin-top-pranto">
            <div class="card border-dark">
                <div class="card-header">
                    <h5>Completed trades :</h5>
                </div>
                <div class="card-body text-dark">
                    <h5 class="card-title">{{App\AdvertiseDeal::where('to_user_id', Auth::id())->where('status', 1)->count()}}</h5>
                </div>
                <div class="card-footer">
                    <a href="{{route('complete.trade')}}" class="btn btn-primary btn-block">Completed Trades</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 text-center margin-top-pranto">
            <div class="card border-dark">
                <div class="card-header">
                    <h5>Cancelled trades :</h5>
                </div>
                <div class="card-body text-dark">
                    <h5 class="card-title">{{App\AdvertiseDeal::where('to_user_id', Auth::id())->where('status', 2)->count()}}</h5>
                </div>
                <div class="card-footer">
                    <a href="{{route('cancel.trade')}}" class="btn btn-primary btn-block">Cancelled Trades</a>
                </div>
            </div>
        </div>


    </div>
@stop
@section('script')


@stop
