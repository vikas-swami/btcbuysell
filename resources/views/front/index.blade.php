@extends('front.layout.master')
@section('style')
<style>
    .price{
        color: green;
        font-size: 16px;
        font-weight: bold;
    }
    .form-control{
        border-color: #337ab7;
    }
    /* @media(max-width:767px){
     
    } */
    </style>
@stop
@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="jumbotron text-center">
                <h1 class="display-5" style="font-weight: bold;color: #212529;">{{$general->banner_title}}</h1>
                <hr class="my-4">
                <p>{{$general->banner_sub_title}}</p>
                @guest
                <a class="btn btn-primary btn-lg " href="{{url('register')}}" role="button"> <i class="fa fa-check-square-o"></i>  Sign Up Now</a>
                    @else
                    <a class="btn btn-primary btn-lg " href="{{url('/home')}}" role="button"> <i class="fa fa-check-square-o"></i>  Dashboard</a>
                @endguest
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card border-dark">
                <div class="card-body text-dark">
                    <form action="{{route('quick.search')}}" method="GET" class="text-center">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <select name="add_type" class="form-control" required>
                                        <option value="">Select Service</option>
                                        <option value="2">Quick Sell</option>
                                        <option value="1">Quick Buy</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <select name="gateway_id" class="form-control" required>
                                        <option value="">Select Coin</option>
                                        @foreach($coin as $data)
                                            <option value="{{$data->id}}">{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <select name="method_id" class="form-control" required>
                                        <option value="">Select Payment Method</option>
                                        @foreach($methods as $data)
                                            <option value="{{$data->id}}">{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <select name="currency_id" class="form-control" required>
                                        <option value="">Select Currency</option>
                                        @foreach($currency as $data)
                                            <option value="{{$data->id}}">{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" role="button">Search</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="row">

        <div class="col-md-12">

            <h4>Sell Bitcoin</h4>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-condensed">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">Buyer</th>
                        <th scope="col">Payment method</th>
                        <th scope="col">Price</th>
                        <th scope="col">Limits</th>
                        <th scope="col">Detail</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sell_btc as $data)
                        @php
                            $bal = \App\UserCryptoBalance::where('user_id', $data->user->id)
                            ->where('gateway_id',$data->gateway_id)->first();
                          $userdef = $data->max_amount;
                          $actual = $data->price*$bal->balance;
                          $max = $userdef>$actual?$actual:$userdef;
                        @endphp

                        <tr @if($data->min_amount >= $max && $data->add_type == 1) style="display: none" @endif>
                            <td>{{$data->user->username}}</td>
                            <td><img style="width: 30px;" src="{{asset('assets/images/crypto/'.$data->method->icon)}}"> {{$data->method->name}}</td>
                            <td class="price">{{$data->price}} {{$data->currency->name}}/{{$data->gateway->currency}}</td>
                            <td>@if($data->add_type == 1)
                                    {{$data->min_amount.' '.$data->currency->name .'-'.round($max,2).' '.$data->currency->name}}
                                @else
                                    {{$data->min_amount.'-'.$data->max_amount.' '.$data->currency->name}}
                                @endif</td>
                             @if($data->user_id != Auth::id())
                            <td><a href="{{route('view', ['id'=>$data->id, 'payment'=>Replace($data->method->name)])}}" class="btn btn-light">Sell</a></td>
                            @else
                            <td><a href="{{route('sell.buy.history')}}" class="btn btn-light">Edit</a></td>
                            @endif
                        </tr>
                        <!-- Cart Tr End -->
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-12">
            <h4>Buy Bitcoin</h4>
            <div class="table-responsive">
                    <table class="table table-hover table-striped table-condensed">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">Buyer</th>
                        <th scope="col">Coin Name</th>
                        <th scope="col">Payment method</th>
                        <th scope="col">Price</th>
                        <th scope="col">Limits</th>
                        <th scope="col">Detail</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($buy_btc as $data)
                        <!-- Cart Tr Start -->
                        @php
                            $bal = \App\UserCryptoBalance::where('user_id', $data->user->id)
                            ->where('gateway_id',$data->gateway_id)->first();
                          $userdef = $data->max_amount;
                          $actual = $data->price*$bal->balance;
                          $max = $userdef>$actual?$actual:$userdef;
                        @endphp

                        <tr @if($data->min_amount >= $max && $data->add_type == 1) style="display: none" @endif>
                            <td>{{$data->user->username}}</td>
                            <td>{{$data->gateway->name}}</td>
                            <td><img style="width: 30px;" src="{{asset('assets/images/crypto/'.$data->method->icon)}}"> {{$data->method->name}}</td>
                            <td class="price">{{$data->price}} {{$data->currency->name}}/{{$data->gateway->currency}}</td>
                            <td>@if($data->add_type == 1)
                                    {{$data->min_amount.' '.$data->currency->name .'-'.round($max,2).' '.$data->currency->name}}
                                @else
                                    {{$data->min_amount.' '.$data->currency->name .'-'.$data->max_amount.' '.$data->currency->name}}
                                @endif</td>
                                @if($data->user_id != Auth::id())
                               <td><a href="{{route('view', ['id'=>$data->id, 'payment'=>Replace($data->method->name)])}}" class="btn btn-light">Buy</a></td>
                                @else
                                <td><a href="{{route('sell.buy.history')}}" class="btn btn-light">Edit</a></td>
                                @endif
                        </tr>
                        <!-- Cart Tr End -->
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <div class="col-md-12">
            <h4>Sell Etherium</h4>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-condensed">
                    <thead class="table-light" >
                    <tr>
                        <th>Buyer</th>
                        <th>Payment method</th>
                        <th>Price</th>
                        <th>Limits</th>
                        <th>Detail</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sell_eth as $data)
                        <!-- Cart Tr Start -->
                        @php
                            $bal = \App\UserCryptoBalance::where('user_id', $data->user->id)
                            ->where('gateway_id',$data->gateway_id)->first();
                          $userdef = $data->max_amount;
                          $actual = $data->price*$bal->balance;
                          $max = $userdef>$actual?$actual:$userdef;
                        @endphp

                        <tr @if($data->min_amount >= $max && $data->add_type == 1) style="display: none" @endif>
                            <td>{{$data->user->username}}</td>

                            <td><img style="width: 30px;" src="{{asset('assets/images/crypto/'.$data->method->icon)}}"> {{$data->method->name}}</td>
                            <td class="price">{{$data->price}} {{$data->currency->name}}/{{$data->gateway->currency}}</td>
                            <td>@if($data->add_type == 1)
                                    {{$data->min_amount.' '.$data->currency->name .'-'.round($max,2).' '.$data->currency->name}}
                                @else
                                    {{$data->min_amount.' '.$data->currency->name .'-'.$data->max_amount.' '.$data->currency->name}}
                                @endif</td>
                                @if($data->user_id != Auth::id())
                                <td><a href="{{route('view', ['id'=>$data->id, 'payment'=>Replace($data->method->name)])}}" class="btn btn-light">Sell</a></td>
                                @else
                                <td><a href="{{route('sell.buy.history')}}" class="btn btn-light">Edit</a></td>
                                @endif
                        </tr>
                        <!-- Cart Tr End -->
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <div class="col-md-12">
            <h4>Buy Etherium</h4>
            <div class="table-responsive">
                 <table class="table table-hover table-striped table-condensed">
                    <thead class="table-light">
                    <tr>
                        <th>Buyer</th>
                        <th>Payment method</th>
                        <th>Price</th>
                        <th>Limits</th>
                        <th>Detail</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($buy_eth as $data)
                        <!-- Cart Tr Start -->
                        @php
                            $bal = \App\UserCryptoBalance::where('user_id', $data->user->id)
                            ->where('gateway_id',$data->gateway_id)->first();
                          $userdef = $data->max_amount;
                          $actual = $data->price*$bal->balance;
                          $max = $userdef>$actual?$actual:$userdef;
                        @endphp

                        <tr @if($data->min_amount >= $max && $data->add_type == 1) style="display: none" @endif>
                            <td>{{$data->user->username}}</td>
                            <td><img style="width: 30px;" src="{{asset('assets/images/crypto/'.$data->method->icon)}}"> {{$data->method->name}}</td>
                            <td class="price">{{$data->price}} {{$data->currency->name}}/{{$data->gateway->currency}}</td>
                            <td>@if($data->add_type == 1)
                                    {{$data->min_amount.' '.$data->currency->name .'-'.round($max,2).' '.$data->currency->name}}
                                @else
                                    {{$data->min_amount.' '.$data->currency->name .'-'.$data->max_amount.' '.$data->currency->name}}
                                @endif</td>
                                @if($data->user_id != Auth::id())
                               <td><a href="{{route('view', ['id'=>$data->id, 'payment'=>Replace($data->method->name)])}}" class="btn btn-light">Buy</a></td>
                                @else
                                <td><a href="{{route('sell.buy.history')}}" class="btn btn-light">Edit</a></td>
                                @endif
                        </tr>
                        <!-- Cart Tr End -->
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

{{-- 
        <div class="col-md-12">
            <h4>Sell Doge</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th>User</th>
                        <th>Payment method</th>
                        <th>Price</th>
                        <th>Limits</th>
                        <th>Detail</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sell_doge as $data)
                        <!-- Cart Tr Start -->
                        @php
                            $bal = \App\UserCryptoBalance::where('user_id', $data->user->id)
                            ->where('gateway_id',$data->gateway_id)->first();
                          $userdef = $data->max_amount;
                          $actual = $data->price*$bal->balance;
                          $max = $userdef>$actual?$actual:$userdef;
                        @endphp

                        <tr @if($data->min_amount >= $max && $data->add_type == 1) style="display: none" @endif>
                            <td>{{$data->user->username}}</td>
                            <td><img style="width: 30px;" src="{{asset('assets/images/crypto/'.$data->method->icon)}}"> {{$data->method->name}}</td>
                            <td>{{$data->price}} {{$data->currency->name}}/{{$data->gateway->currency}}</td>
                            <td>@if($data->add_type == 1)
                                    {{$data->min_amount.' '.$data->currency->name .'-'.round($max,2).' '.$data->currency->name}}
                                @else
                                    {{$data->min_amount.' '.$data->currency->name .'-'.$data->max_amount.' '.$data->currency->name}}
                                @endif</td>
                            <td><a href="{{route('view', ['id'=>$data->id, 'payment'=>Replace($data->method->name)])}}" class="btn btn-light">Sell</a></td>
                        </tr>
                        <!-- Cart Tr End -->
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div> --}}

        {{-- <div class="col-md-12">
            <h4>Buy Doge</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th>User</th>
                        <th>Payment method</th>
                        <th>Price</th>
                        <th>Limits</th>
                        <th>Detail</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($buy_doge as $data)
                        <!-- Cart Tr Start -->
                        @php
                            $bal = \App\UserCryptoBalance::where('user_id', $data->user->id)
                            ->where('gateway_id',$data->gateway_id)->first();
                          $userdef = $data->max_amount;
                          $actual = $data->price*$bal->balance;
                          $max = $userdef>$actual?$actual:$userdef;
                        @endphp

                        <tr @if($data->min_amount >= $max && $data->add_type == 1) style="display: none" @endif>
                            <td>{{$data->user->username}}</td>
                            <td><img style="width: 30px;" src="{{asset('assets/images/crypto/'.$data->method->icon)}}"> {{$data->method->name}}</td>
                            <td>{{$data->price}} {{$data->currency->name}}/{{$data->gateway->currency}}</td>
                            <td>@if($data->add_type == 1)
                                    {{$data->min_amount.' '.$data->currency->name .'-'.round($max,2).' '.$data->currency->name}}
                                @else
                                    {{$data->min_amount.' '.$data->currency->name .'-'.$data->max_amount.' '.$data->currency->name}}
                                @endif</td>
                            <td><a href="{{route('view', ['id'=>$data->id, 'payment'=>Replace($data->method->name)])}}" class="btn btn-light">Buy</a></td>
                        </tr>
                        <!-- Cart Tr End -->
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div> --}}


        {{-- <div class="col-md-12">
            <h4>Sell Litecoin</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th>User</th>
                        <th>Payment method</th>
                        <th>Price</th>
                        <th>Limits</th>
                        <th>Detail</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($sell_lite as $data)
                        <!-- Cart Tr Start -->
                        @php
                            $bal = \App\UserCryptoBalance::where('user_id', $data->user->id)
                            ->where('gateway_id',$data->gateway_id)->first();
                          $userdef = $data->max_amount;
                          $actual = $data->price*$bal->balance;
                          $max = $userdef>$actual?$actual:$userdef;
                        @endphp

                        <tr @if($data->min_amount >= $max && $data->add_type == 1) style="display: none" @endif>
                            <td>{{$data->user->username}}</td>
                            <td><img style="width: 30px;" src="{{asset('assets/images/crypto/'.$data->method->icon)}}"> {{$data->method->name}}</td>
                            <td>{{$data->price}} {{$data->currency->name}}/{{$data->gateway->currency}}</td>
                            <td>@if($data->add_type == 1)
                                    {{$data->min_amount.' '.$data->currency->name .'-'.round($max,2).' '.$data->currency->name}}
                                @else
                                    {{$data->min_amount.' '.$data->currency->name .'-'.$data->max_amount.' '.$data->currency->name}}
                                @endif</td>
                            <td><a href="{{route('view', ['id'=>$data->id, 'payment'=>Replace($data->method->name)])}}" class="btn btn-light">Sell</a></td>
                        </tr>
                        <!-- Cart Tr End -->
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div> --}}


        {{-- <div class="col-md-12">
            <h4>Buy Litecoin</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th>User</th>
                        <th>Payment method</th>
                        <th>Price</th>
                        <th>Limits</th>
                        <th>Detail</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($buy_lite as $data)
                        <!-- Cart Tr Start -->
                        @php
                            $bal = \App\UserCryptoBalance::where('user_id', $data->user->id)
                            ->where('gateway_id',$data->gateway_id)->first();
                          $userdef = $data->max_amount;
                          $actual = $data->price*$bal->balance;
                          $max = $userdef>$actual?$actual:$userdef;
                        @endphp

                        <tr @if($data->min_amount >= $max && $data->add_type == 1) style="display: none" @endif>
                            <td>{{$data->user->username}}</td>
                            <td><img style="width: 30px;" src="{{asset('assets/images/crypto/'.$data->method->icon)}}"> {{$data->method->name}}</td>
                            <td>{{$data->price}} {{$data->currency->name}}/{{$data->gateway->currency}}</td>
                            <td>@if($data->add_type == 1)
                                    {{$data->min_amount.' '.$data->currency->name .'-'.round($max,2).' '.$data->currency->name}}
                                @else
                                    {{$data->min_amount.' '.$data->currency->name .'-'.$data->max_amount.' '.$data->currency->name}}
                                @endif
                            </td>
                            <td><a href="{{route('view', ['id'=>$data->id, 'payment'=>Replace($data->method->name)])}}" class="btn btn-light">Buy</a></td>
                        </tr>
                        <!-- Cart Tr End -->
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div> --}}



    </div>


@stop
@section('script')


@stop