@extends('front.layout.master')

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
                    <strong style="font-size: 24px;">Create Support Ticket</strong>
                </div>
                <div class="card-body">

                    <form class="form-horizontal" action="{{route('ticket.store')}}" method="post">
                        @csrf

                        <div class="form-group">
                            <strong>Subject Name:</strong>
                            <input class="form-control" type="text"  value="{{ old('subject') }}" required name="subject" placeholder="Title Name">
                        </div>

                        <div class="form-group">
                            <strong>Message:</strong>
                            <textarea class="form-control" name="detail" rows="10" placeholder="Write Here Your Message...">{!! old('detail') !!}</textarea>

                        </div>


                        <input type="submit" class="btn btn-primary btn-block" value="Post">
                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection