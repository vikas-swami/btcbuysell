@extends('front.layout.master')

@section('style')

@endsection
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
                    <strong style="font-size: 24px;">View Ticket</strong>

                    @if($ticket_object->status == 1)
                        <button class="btn btn-warning  pull-right "> Opened</button>
                    @elseif($ticket_object->status == 2)
                        <button type="button" class="btn btn-success  pull-right ">  Answered </button>
                    @elseif($ticket_object->status == 3)
                        <button type="button" class="btn btn-info pull-right "> Customer Reply </button>

                    @elseif($ticket_object->status == 9)
                        <button type="button" class="btn btn-danger pull-right ">  Closed </button>
                    @endif

                    <a href="{{route('ticket.close', $ticket_object->ticket)}}" class="btn btn-danger pull-right" style="margin-right: 10px;" >Click To Make Close</a>

                </div>
                <div class="card-body">
                    <h4 class="card-title text-center">#{{$ticket_object->ticket}} - {{$ticket_object->subject}}</h4>
                    <br>
                    <br>

                    <form method="POST" action="{{route('store.customer.reply', $ticket_object->ticket)}}" accept-charset="UTF-8" >
                    {{csrf_field()}}

                        @foreach($ticket_data as $data)
                            <div class="card" style="margin-top: 10px;">
                                <div class="card-header">
                                    @if($data->type == 1)
                                        <p><span style="color: #0e76a8">{{Auth::user()->username}}</span> , <small>{{ \Carbon\Carbon::parse($data->updated_at)->format('F dS, Y - h:i A') }}</small></p>
                                    @else
                                        <p><span style="color: #0e76a8">{{$general->sitename}}</span> , <small>{{ \Carbon\Carbon::parse($data->updated_at)->format('F dS, Y - h:i A') }}</small></p>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{!! $data->comment !!}</p>
                                </div>
                            </div>
                        @endforeach


                        <div class="form-group padding-pranto-top">
                            <strong >Reply: </strong>
                            <textarea class="form-control" name="comment" rows="10"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-check"></i> Post</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection