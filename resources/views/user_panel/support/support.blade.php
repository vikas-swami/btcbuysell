@extends('front.layout.master')
@section('style')

@stop
@section('body')
    <div class="row padding-pranto-top padding-pranto-bottom">
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
                    <strong style="font-size: 24px;">Support Ticket</strong>

                    <a class="btn btn-primary pull-right " href="{{route('add.new.ticket')}}"> <i class="fa fa-plus"></i> New Ticket</a>
                </div>
                <div class="card-body">

                    <div class="alert alert-info" role="alert">
                        Dear users,Do not open multiple tickets, since it will increase our response time.
                    </div>

                    <div class="table-responsive">


                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th style="width:10% !important;"> Ticket Id </th>
                                <th> Subject </th>
                                <th> Raised Time </th>
                                <th> Status </th>
                                <th> Action </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $all_ticket as $key=>$data)
                                <tr>
                                    <td>{{$data->ticket}}</td>
                                    <td><b>{{$data->subject}}</b></td>
                                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('F dS, Y - h:i A') }}</td>
                                    <td>
                                        @if($data->status == 1)
                                            <button class="btn btn-warning"> Opened</button>
                                        @elseif($data->status == 2)
                                            <button type="button" class="btn btn-success">  Answered </button>
                                        @elseif($data->status == 3)
                                            <button type="button" class="btn btn-info"> Customer Reply </button>
                                        @elseif($data->status == 9)
                                            <button type="button" class="btn btn-danger">  Closed </button>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('ticket.customer.reply', $data->ticket )}}" class="btn btn-secondary"><b>View</b></a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>

                    {{$all_ticket->links()}}



                </div>
            </div>
        </div>

    </div>
@endsection