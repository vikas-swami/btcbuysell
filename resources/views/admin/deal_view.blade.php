@extends('admin.layout.master')

@section('body')
    <div class="row">
        <div class="col-md-6">
            <div class="tile">
                <h4 class="tile-title">
                    <i class="fa fa-user"></i> @if($trans->add_type == 1) Buy Request @else Sell Request @endif
                    from <a href="{{route('user.single', $trans->from_user->id)}}"> {{$trans->from_user->username}} </a> to <a href="{{route('user.single', $trans->to_user->id)}}">  {{$trans->to_user->username}} </a></h4>
                <div class="tile-body">
                    <hr>
                    <h5>Trans ID: {{$trans->trans_id}}</h5>
                    <h5 class="bold">Transaction status:
                        @if($trans->status == 0)
                            <span class="badge  badge-warning"> Processing </span>
                        @elseif($trans->status == 1)
                            <label class="badge  badge-success"> Paid Complete </label>
                        @elseif($trans->status == 9)
                            <span class="badge  badge-info"> Paid Approval </span>
                        @else
                            <span class="badge  badge-danger"> Canceled </span>
                        @endif
                    </h5>
                    <h5 class="bold">Requested At : {{ $trans->created_at }}</h5>
                    <hr>
                    <p>
                        <strong>Updated At : {{$trans->updated_at }}</strong>
                        <br>
                    </p>
                </div>
            </div>

        </div>



        <div class="col-md-6">
            <div class="tile">
                <h4 class="tile-title">Price: {{$trans->price}} {{$trans->currency->name}}/{{$trans->gateway->currency}}</h4>
                <div class="tile-body">
                    <hr>
                    <h5 class="bold">Amount In USD : {{ $trans->usd_amount }} USD</h5>
                    <h5 class="bold">Amount In {{$trans->gateway->currency}} : {{ round($trans->coin_amount,8) }} {{$trans->gateway->currency}}</h5>
                    <hr>
                    <p>
                        <strong>Gateway : {{$trans->gateway->name }}</strong><br>
                        <strong>Payment Method : {{$trans->method->name }}</strong><br>
                        <strong>Currency Type : {{$trans->currency->name }}</strong><br>

                    </p>
                </div>
            </div>

        </div>


        <div class="col-md-12">
            <div class="tile">
                <h4 class="tile-title">Terms & Payment Detal</h4>
                <div class="tile-body">
                    <hr>
                    <h5 class="bold">Terms Detail :</h5>
                    {!! $trans->term_detail !!}
                    <hr>
                    <h5 class="bold">Payment Detail :</h5>
                    {!! $trans->payment_detail !!}
                </div>
            </div>

        </div>



    </div>

@endsection

