@extends('front.layout.master')
@section('style')

@stop
@section('body')
    <div class="row  padding-pranto-top padding-pranto-bottom">
        <div class="col-md-12">

            <div class="card">

                <div class="card-header">
                    <h4>Trade Advertise History</h4>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th>Advertise Type</th>
                                <th>GateWay Name</th>
                                <th>Payment Method Name</th>
                                <th>Min-Max</th>
                                <th>Raised Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($addvertise as $data)
                                <tr>
                                    <td>
                                        @if($data->add_type == 1)
                                            <span class="badge badge-primary">Want To Sell</span>
                                        @else
                                            <span class="badge badge-success">Want To Buy</span>
                                        @endif
                                    </td>
                                    <td>{{$data->gateway->name}}</td>
                                    <td>{{$data->method->name}}</td>

                                    <td>{{$data->min_amount.' '.$data->currency->name .'-'. $data->max_amount.' '.$data->currency->name}}</td>
                                    <td>{{ date('g:ia \o\n l jS F Y', strtotime($data->created_at)) }}</td>
                                    <td><a href="{{route('sell_buy.edit', $data->id)}}" class="btn btn-secondary">Edit</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{$addvertise->links()}}

                </div>

            </div>

        </div>
    </div>
@stop

@section('script')
<script>
    $(document).ready(function () {
        $("#crypto_id").change(function () {

           var crypto = $(this).val();
           var token = "{{csrf_token()}}";

           $.ajax({
                type :"POST",
                url :"{{route('currency.check')}}",
                data:{
                    'crypto' : crypto,
                    '_token' : token
               },
               success:function(data){
                    $("#currency").val(data.code);

               }
           });
        });
    });
</script>
@stop