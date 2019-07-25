@extends('front.layout.master')
@section('style')

@stop
@section('body')
    @php
        $bal = \App\UserCryptoBalance::where('user_id', $coin->user->id)
        ->where('gateway_id',$coin->gateway_id)->first();
      $userdef = $coin->max_amount;
      $actual = $coin->price*$bal->balance;
      if ($coin->add_type == 1){
      $max = $userdef>$actual?$actual:$userdef;
      }else{
      $max = $coin->max_amount;
      }

    @endphp

    <div class="row">
        <div class="col-md-12">
            <div class="pb-2 mt-4 mb-2 border-bottom">
                {{$coin->add_type == 2 ? 'Sell':'Buy'}} {{$coin->gateway->name}} using <img style="width: 40px;" src="{{asset('assets/images/crypto/'.$coin->method->icon)}}"> {{$coin->method->name}}
            </div>
        </div>

        <div class="col-md-12">
            <h6 class="text-center">{{url('/')}} user: {{$coin->user->name}} wishes to {{$coin->add_type == 1 ? 'sell':'buy'}} {{$coin->gateway->name}} to you.</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 col-sm-6"></div>
        <div class="col-md-6 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5><strong>Price : <span style="color: #17c325"><i class="fa fa-money"></i> {{$coin->price}} {{$coin->currency->name}} / {{$coin->gateway->currency}}</span></strong></h5>

                    <p><strong>User : </strong><span><a href="{{route('user.profile.view', $coin->user->username)}}" style="color: black"><i class="fa fa-user"></i> {{$coin->user->username}}</a></span></p>

                    <p><strong>Payment Method : </strong><span><img style="width: 30px;" src="{{asset('assets/images/crypto/'.$coin->method->icon)}}"> {{$coin->method->name}}</span></p>

                    <p><strong>Trade Limit : </strong><span>{{$coin->min_amount}} - {{$max}} {{$coin->currency->name}}</span></p>
                </div>
                <div class="card-footer">
                        <button type="button" data-toggle="modal" data-target="#termModal" class="btn btn-dark btn-block">{{$coin->user->name}}'s Terms</button>
                        <button type="button" data-toggle="modal" data-target="#paymentModal" class="btn btn-secondary btn-block">{{$coin->user->name}}'s Payment Detail</button>
                </div>
            </div>
        </div>

        {{-- <div class="col-md-4">
            <div class="card margin-top-pranto">
                <div class="card-body">
                    <br>
                    
                </div>
            </div>
        </div>--}}
     </div> <hr> 

    <div class="row">
        @guest

            <div class="col-md-8 offset-md-2 col-sm-12 padding-pranto-bottom">
                <div class="login-admin login-admin1">
                    <div class="login-form">
                        <a href="{{url('/register')}}" class="btn btn-primary" style="display: block;text-decoration:none">Sign Up Now</a>
                    </div>

                </div>
            </div>

        @else


            <div class="col-md-8 offset-md-2 col-sm-12 padding-pranto-bottom">

                <div class="card">
                    <div class="card-header text-center">
                       <h3> How Much Want To {{$coin->add_type == 2 ? 'Sell':'Buy'}} ?</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('store.deal', $coin->id)}}" class="form-horizontal" method="POST">
                        @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="input-group">
                                        <input type="text" id="amount" name="amount"  placeholder="How Much Want To {{$coin->add_type == 2 ? 'Sell':'Buy'}}" class="form-control"  aria-describedby="inputGroupPrepend2" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupPrepend2">{{$coin->currency->name}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" readonly id="inCoin" placeholder="In {{$coin->gateway->name}}" aria-describedby="inputGroupPrepend2" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupPrepend2">{{$coin->gateway->currency}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <textarea class="form-control" name="detail" rows="5" placeholder="Contact Message And Others Information..."></textarea>
                            </div>

                            <input type="submit" class="btn btn-primary btn-block" style="display: none" id="submit" value="Send Request">

                        </form>
                    </div>
                </div>

            </div>
        @endguest
    </div>


    <div id="termModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: green">Terms Of <strong> {{$coin->user->name}}</strong></h4>
                </div>
                <div class="modal-body">
                    <p class="text-center">{!! $coin->term_detail !!}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div id="paymentModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: green">Payment Detail of <strong> {{$coin->user->name}}</strong></h4>
                </div>
                <div class="modal-body">
                    <p class="text-center">{!! $coin->payment_detail !!}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


@stop

@section('script')
<script>
    $(document).ready(function () {
        var min = "{{$coin->min_amount}}";
        var max = "{{$max}}";
        var price = "{{$price}}";

        $(document).on('keyup', "#amount",function(){
            var val = $(this).val();

            if(parseFloat(val) >= parseFloat(min) && parseFloat(val) <= parseFloat(max)){
                $("#amount").css("background-color", "#87E9B9");
                $("#submit").css("display", "block");
                $("#inCoin").val(parseFloat(val)/price);

            }else {
                $("#amount").css("background-color", "#F59898");
                $("#submit").css("display", "none");
                $("#inCoin").val(' ');
            }


        });
    });
</script>
@stop