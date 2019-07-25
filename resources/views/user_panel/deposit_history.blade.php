@extends('front.layout.master')
@section('style')

@stop
@section('body')
    <div class="row  padding-pranto-top padding-pranto-bottom">
        <div class="col-md-12">

            <div class="card">

                <div class="card-header">
                    <h4>{{$title}}</h4>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th>Trans ID</th>
                                <th>Gateway</th>
                                <th>Amount</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($trans as $data)
                                <tr >
                                    <td>{{$data->trx}}</td>
                                    <td>{{$data->gateway->name}}</td>
                                    <td>{{$data->amount}} {{$data->gateway->currency}}</td>
                                    <td>{{$data->created_at}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    {{$trans->links()}}

                </div>

            </div>

        </div>
    </div>
@stop

@section('script')

@stop