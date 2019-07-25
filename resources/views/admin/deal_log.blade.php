@extends('admin.layout.master')
@section('css')
    <style>
        #imaginary_container{
            margin-top:20%; /* Don't copy this */
        }
        .stylish-input-group .input-group-addon{
            background: white !important;
        }
        .stylish-input-group .form-control{
            border-right:0;
            box-shadow:0 0 0;
            border-color:#ccc;
        }
        .stylish-input-group button{
            border:0;
            background:transparent;
        }
    </style>
@stop
@section('body')
    <div class="row">

        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title ">Search By Trans ID</h3>
                <div class="tile-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <form class="form-horizontal" method="GET" action="{{route('trans.search')}}">

                                    <div class="input-group stylish-input-group">
                                        <input type="text" class="form-control has-error bold" name="trans_id" required placeholder="Search By Trans ID" >

                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                            <button type="submit">
                                                    <span><i class="fa fa-search"></i></span>
                                                </button>
                                            </span>
                                        </div>

                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title ">{{$page_title}}</h3>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover order-column" id="">
                            <thead>
                            <tr>
                                <th>Trans Id</th>
                                <th>Trans Title</th>
                                <th>Amount</th>
                                <th>Created At</th>
                                <th>Detail</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($trans as $data)
                                <tr>
                                    <td>{{$data->trans_id}}</td>
                                    <td>
                                        @if($data->add_type == 1) Buy Request @else Sell Request @endif
                                        from {{$data->from_user->username}} to {{$data->to_user->username}}
                                    </td>
                                    <td>{{$data->amount_to}} {{$data->currency->name}}</td>
                                    <td>{{$data->created_at}}</td>
                                    <td><a class="btn btn-primary" href="{{route('deal.view.admin', $data->trans_id)}}" style="color: white">Detail</a></td>
                                </tr>
                            @endforeach
                            <tbody>
                        </table>
                        {{$trans->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection