@extends('front.layout.master')
@section('style')

@stop
@section('body')
    <div class="row ">

        <div class="col-md-12 col-sm-12">

            <div class="jumbotron text-center">

                <div class="row">

                    <div class="col-md-4">
                        <div class="card text-uppercase">
                            <div class="card-header">
                                <strong> Personal Detail</strong>
                            </div>
                            <ul class="list-group list-group-flush ">
                                <li class="list-group-item"> <i class="fa fa-user"></i> {{$user->username}}</li>
                                <li class="list-group-item"> <i class="fa fa-user-circle-o"></i> {{$user->name}}</li>
                                <li class="list-group-item"> <i class="fa fa-map-marker"></i> {{$user->city}}</li>
                                <li class="list-group-item"> <i class="fa fa-globe"></i> {{$user->country}}</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4 margin-top-pranto">
                        <div class="card">
                            <div class="card-header text-uppercase">
                                Information of <strong> {{$user->username}}</strong>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"> <strong>Trade volume of BTC:</strong> {{round($trade_btc,8)}} BTC</li>
                                <li class="list-group-item"> <strong>Trade volume of ETH:</strong> {{round($trade_etc,8)}} ETH</li>
                                {{-- <li class="list-group-item"> <strong>Trade volume of DOGE:</strong> {{round($trade_doge,8)}} DOGE</li> --}}
                                {{-- <li class="list-group-item"> <strong>Trade volume of LTC:</strong> {{round($trade_lite,8)}} LTC</li> --}}
                                <li class="list-group-item"> <strong>First purchase:</strong> @if(!empty($first_buy)) {{\Carbon\Carbon::createFromTimeStamp(strtotime($first_buy->created_at))->diffForHumans()}} @else NA @endif</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4 margin-top-pranto">
                        <div class="card">
                            <div class="card-header text-uppercase">
                               Others Information
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"> <strong>Account created:</strong>@if(!empty($user->created_at)) {{\Carbon\Carbon::createFromTimeStamp(strtotime($user->created_at))->diffForHumans()}} @else NA @endif</li>
                                <li class="list-group-item"> <strong>Last seen:</strong>@if(!empty($last_login->created_at)) {{\Carbon\Carbon::createFromTimeStamp(strtotime($last_login->created_at))->diffForHumans()}}@else NA @endif</li>
                                <li class="list-group-item"> <strong>Email:</strong> @if($user->email_verify == 1) <i style="color: green;" class="fa fa-check"></i> Verified @else <i style="color: red;" class="fa fa-times"></i> Unverified  @endif</li>
                                <li class="list-group-item"> <strong>Phone Number:</strong> @if($user->phone_verify == 1) <i style="color: green;" class="fa fa-check"></i> Verified @else <i style="color: red;" class="fa fa-times"></i> Unverified  @endif</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <hr>
        <div class="col-md-12  col-sm-12">

            <div class="jumbotron text-center">

                <div class="row">
                    <div class="col-md-12">
                        <div class="jumbotron text-center" style="background-color: white;padding:10px;">
                            <h2 class="text-center">All Transaction</h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 margin-top-pranto">
                        <div class="card text-uppercase">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"> Total Bitcoin Buy <strong>{{ \App\AdvertiseDeal::where('to_user_id', $user->id)->where('gateway_id', 505)->where('add_type',1)->count() }}</strong></li>
                                <li class="list-group-item"> Total Bitcoin Sell <strong>{{ \App\AdvertiseDeal::where('from_user_id', $user->id)->where('gateway_id', 505)->where('add_type',2)->count() }}</strong></li>
                            </ul>
                        </div>
                    </div>


                    <div class="col-md-6 margin-top-pranto">
                        <div class="card text-uppercase">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"> Total Etherium Buy <strong>{{ \App\AdvertiseDeal::where('to_user_id', $user->id)->where('gateway_id', 506)->where('add_type',1)->count() }}</strong></li>
                                <li class="list-group-item"> Total Etherium Sell <strong>{{ \App\AdvertiseDeal::where('from_user_id', $user->id)->where('gateway_id', 506)->where('add_type',1)->count() }}</strong></li>
                            </ul>
                        </div>
                    </div>
                    {{-- <div class="col-md-3 margin-top-pranto">
                        <div class="card text-uppercase">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"> Total Dogecoin Buy <strong>{{ \App\AdvertiseDeal::where('to_user_id', $user->id)->where('gateway_id', 509)->where('add_type',1)->count() }}</strong></li>
                                <li class="list-group-item"> Total Dogecoin Sell <strong>{{ \App\AdvertiseDeal::where('from_user_id', $user->id)->where('gateway_id', 509)->where('add_type',2)->count() }}</strong></li>
                            </ul>
                        </div>
                    </div> --}}
                    {{-- <div class="col-md-3 margin-top-pranto">
                        <div class="card text-uppercase">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"> Total Litecoin Buy <strong>{{ \App\AdvertiseDeal::where('to_user_id', $user->id)->where('gateway_id', 510)->where('add_type',1)->count() }}</strong></li>
                                <li class="list-group-item"> Total Litecoin Sell <strong>{{ \App\AdvertiseDeal::where('from_user_id', $user->id)->where('gateway_id', 510)->where('add_type',2)->count() }}</strong></li>
                            </ul>
                        </div>
                    </div> --}}



            </div>
        </div>


        <hr>

        <div class="col-md-12">

            <div class="card">

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th>Coin Name</th>
                                <th>Payment method</th>
                                <th>Price</th>
                                <th>Limits</th>
                                <th>Detail</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($coin as $data)
                                <!-- Cart Tr Start -->
                                @php
                                    $bal = \App\UserCryptoBalance::where('user_id', $data->user->id)
                                    ->where('gateway_id',$data->gateway_id)->first();
                                  $userdef = $data->max_amount;
                                  $actual = $data->price*$bal->balance;
                                  $max = $userdef>$actual?$actual:$userdef;
                                @endphp

                                <tr @if($data->min_amount >= $max && $data->add_type == 1) style="display: none" @endif>
                                    <td>{{$data->gateway->name}}</td>
                                    <td><img style="width: 30px;" src="{{asset('assets/images/crypto/'.$data->method->icon)}}"> {{$data->method->name}}</td>
                                    <td>{{$data->price}} {{$data->currency->name}}/{{$data->gateway->currency}}</td>
                                    <td>@if($data->add_type == 1)
                                            {{$data->min_amount.' '.$data->currency->name .'-'.round($max,2).' '.$data->currency->name}}
                                        @else
                                            {{$data->min_amount.' '.$data->currency->name .'-'.$data->max_amount.' '.$data->currency->name}}
                                        @endif
                                    </td>
                                    <td><a class="btn btn-secondary" href="{{route('view', ['id'=>$data->id, 'payment'=>Replace($data->method->name)])}}">{{$data->add_type == 2 ? 'Sell':'Buy'}}</a></td>
                                </tr>
                                <!-- Cart Tr End -->
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    {{$coin->links()}}

                </div>

            </div>

        </div>

    </div>


@stop

@section('script')

@stop