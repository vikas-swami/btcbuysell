@extends('front.layout.master')
@section('style')

@stop
@section('body')
<div class="row">
    @if (count($errors) > 0)
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong class="col-md-12"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Alert!</strong>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        </div>
    @endif

    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
               <strong style="font-size: 24px;"> Create a Coin Trade Advertisement</strong>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info" role="alert">
                            Dear users, Each completed trade costs advertisers <strong>{{$general->trx_charge}}% Charge</strong> of the total trade amount.
                            You may only use payment accounts that are registered in your own name.
                            You must provide your payement details in the advertisement or in the trade chat.
                        </div>
                    </div>
                    <div class="col-md-12 text-center" style="color: #ff1501">
                        What kind of trade advertisement do you wish to create? If you wish to sell coins make sure you have coins in your Local wallet.
                    </div>
                </div>

                <br>
                <br>

                <form action="{{route('sell.buy')}}" method="post">
                @csrf

                    <div class="form-row">

                        <div class="form-group col-md-4">
                            <strong>Select Type</strong>
                            <select name="add_type" id="coin" class="form-control">
                                <option value="1">Sell</option>
                                <option value="2">Buy</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <strong>Select Coin</strong>
                            <select name="gateway_id" id="coin" class="form-control">
                                <option value="">Select Coin</option>
                                @foreach($coin as $data)
                                    <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <strong>Payment Method</strong>
                            <select name="crypto_id"  class="form-control">
                                <option value=" ">Select Method</option>
                                @foreach($methods as $data)
                                    <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group col-md-4">
                            <strong>Currency</strong>
                            <select name="currency" id="currency" class="form-control">
                                <option value="">Select Currency</option>
                                @foreach($currency as $data)
                                    <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <strong>Margin</strong>
                            <div class="input-group">
                                <input class="form-control" type="number" value="0" name="margin" id="margin" placeholder="Margin">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">%</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <strong>Price equation</strong>
                            <input class="form-control" type="text" id="price" readonly>
                        </div>

                    </div>

                    <hr>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <strong>Min. transaction limit</strong>
                            <div class="input-group">
                                <input class="form-control" type="text" name="min_amount" value="{{old('min_amount')}}"  placeholder="Min Amount">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">{{$general->currency}}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <strong>Max. transaction limit</strong>
                            <div class="input-group">
                                <input class="form-control" type="text" name="max_amount" value="{{old('max_amount')}}" placeholder="Max Amount">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">{{$general->currency}}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <strong>Terms of Trade</strong>
                            <textarea class="form-control" name="term_detail" rows="5">{!! old('detail') !!}</textarea>
                        </div>

                        <div class="form-group col-md-6">
                            <strong>Payment Details</strong>
                            <textarea class="form-control" name="payment_detail" rows="5">{!! old('payment_detail') !!}</textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="checkbox-check">
                                <input type="checkbox" name="agree" value="1" required>
                                I Agree With All <a href="{{route('terms.index')}}">Terms</a> & <a href="{{route('policy.index')}}">Policy</a></label>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Publish Advertise</button>


                </form>
            </div>
        </div>

    </div>

</div>
@stop

@section('script')


<script>
    $(document).ready(function () {
        $(document).on('change',"#coin", function () {

           var crypto = $(this).find(":selected").val();

            if(crypto == 505){
                var price = '{{$btc_usd}}';
                var name = 'BTC';
                getPrice(price);
            }else if(crypto == 506){
                var price = '{{$eth_usd}}';
                var name = 'ETH';
                getPrice(price);
            }else if(crypto == 509){
                var price = '{{$doge_usd}}';
                var name = 'DOGE';
                getPrice(price);

            }else{
                var price = '{{$lite_usd}}';
                var name = 'LTE';
                getPrice(price);
            }

            function getPrice(price) {

                var cur = $("#currency").val();
                var token = "{{csrf_token()}}";

                $("#margin").val('0');

                $.ajax({
                    type :"POST",
                    url :"{{route('currency.check')}}",
                    data:{
                        'crypto' : cur,
                        '_token' : token
                    },

                    success:function(data){
                        $("#sizing-addon1").text(data.name);
                        $("#sizing-addon2").text(data.name);

                        if ($("#margin").val() == 0){
                            $("#price").val(data.usd_rate*price +' '+data.name+' to '+name);
                        }

                        $("#margin").bind('keyup mouseup', function (){

                            var margin = $(this).val();
                            if (margin == 0){
                                var afterMargin = (data.usd_rate*price * 1)/100;
                                $("#price").val(((data.usd_rate*price)+afterMargin) +' '+data.name+' to '+name);
                            }
//                            if (margin != 0){
                                var afterMargin = (data.usd_rate*price * margin)/100;
                                $("#price").val(((data.usd_rate*price)+afterMargin) +' '+data.name+' to '+name);
//                            }

                        });

                    }
                });

            }

            $(document).on('change',"#currency", function () {

                $("#margin").val('0');

                var cur = $(this).find(":selected").val();
                var token = "{{csrf_token()}}";

                $.ajax({
                    type :"POST",
                    url :"{{route('currency.check')}}",
                    data:{
                        'crypto' : cur,
                        '_token' : token
                    },

                    success:function(data){
                        $("#sizing-addon1").text(data.name);
                        $("#sizing-addon2").text(data.name);

                        if ($("#margin").val() == 0){
                            $("#price").val(data.usd_rate*price +' '+data.name+' to '+name);
                        }

                        $("#margin").bind('keyup mouseup', function (){

                            var margin = $(this).val();
                            if (margin == 0){
                                var afterMargin = (data.usd_rate*price * 1)/100;
                                $("#price").val(((data.usd_rate*price)+afterMargin) +' '+data.name+' to '+name);
                            }

//                            if (margin != 0){
                                var afterMargin = (data.usd_rate*price * margin)/100;
                                $("#price").val(((data.usd_rate*price)+afterMargin) +' '+data.name+' to '+name);
//                            }

                        });

                    }
                });
            });

        });
    });
</script>
@stop