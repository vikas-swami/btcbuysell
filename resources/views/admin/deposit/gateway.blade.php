@extends('admin.layout.master')

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title ">Your CoinPayments Credential</h3>
                <div class="tile-body">
                   <form method="post" action="{{route('update.gateway')}}">
                       @csrf
                       <div class="form-group">
                           <h5 for="val1">
                               <storng>Private Key</storng>
                           </h5>
                           <input type="text" value="{{$first_gateway->val1}}"
                                  class="form-control" id="val1" name="val1">
                       </div>

                       <div class="form-group">
                           <h5 for="val2"><strong>Public Key</strong></h5>
                           <input type="text" value="{{$first_gateway->val2}}"
                                  class="form-control" id="val2" name="val2">
                       </div>

                       <button class="btn btn-success btn-block">Update</button>
                   </form>
                </div>
                <br>
                <br>



                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Icon</th>
                                <th>Coin Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($gateways as $k=>$gateway)
                                <tr>
                                    <td>{{ ++$k }}</td>
                                    <td><img style="height: 80px;" src="{{ asset('assets/images/gateway') }}/{{$gateway->id}}.jpg" alt=""/></td>
                                    <td>{!! $gateway->name !!}</td>
                                </tr>
                            @endforeach
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>







@endsection