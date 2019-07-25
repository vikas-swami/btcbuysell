@extends('admin.layout.master')

@section('css')
    <style>
        rect:nth-child(even){
            fill: #17a2b8;
        }
        rect:nth-child(odd){
            fill: #19b952;
        }
        .card-header {
            padding: 0.40rem 1.25rem;
            /*background: #8c7ae6;*/
            background: #2f353b;
            color: white;
            font-size: 20px;
        }

        .widget-small .info h4{
            font-size: 15px;
        }
        .widget-small{
            margin-bottom: 0px;
        }

        .card{
            margin-bottom: 20px!important;
            border: 1px solid #2f353b;
        }

        @media (min-width:312px) and (max-width:480px) {
            .widget-small {
                margin-bottom: 20px!important;
            }
        }
    </style>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
@stop
@section('body')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="icon fa fa-users"></i> User Panel Shortcut
                </div>
                <div class="card-body">

                    <div class="row" >
                        <div class="col-md-3">
                            <a href="{{url('/admin/users')}}" class="text-decoration">
                                <div class="widget-small primary"><i class="icon fa fa-users fa-3x"></i>
                                    <div class="info">
                                        <h4>Total Users</h4>
                                        <p><b>{{$user}}</b></p>
                                    </div>
                                </div>
                            </a>
                        </div>


                        <div class="col-md-3">
                            <a href="{{route('users.active')}}" class="text-decoration">
                                <div class="widget-small info "><i class="icon fa fa-check fa-3x"></i>
                                    <div class="info">
                                        <h4>Active Users</h4>
                                        <p><b>{{$user_active}}</b></p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3">
                            <a href="{{route('users.email.verified')}}" class="text-decoration">
                                <div class="widget-small primary "><i class="icon fa fa-envelope fa-3x"></i>
                                    <div class="info">
                                        <h4>E-Unverified Users</h4>
                                        <p><b>{{$email_active}}</b></p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3">
                            <a href="{{route('users.phone.verified')}}" class="text-decoration">
                                <div class="widget-small danger "><i class="icon fa fa-phone fa-3x"></i>
                                    <div class="info">
                                        <h4>P-Unverified Users</h4>
                                        <p><b>{{$phone_active}}</b></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="icon fa fa-bar-chart"></i> Statistics
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <a href="{{url('/admin/deals')}}" class="text-decoration">
                                <div class="widget-small primary "><i class="icon fa fa-handshake-o fa-3x"></i>
                                    <div class="info">
                                        <h4>Complete Deal</h4>
                                        <p><b>{{\App\CryptoAddvertise::where('status', 1)->count()}}</b></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <a href="{{url('/admin/crypto')}}" class="text-decoration">
                                <div class="widget-small info "><i class="icon fa fa-money fa-3x"></i>
                                    <div class="info">
                                        <h4>Total Methods</h4>
                                        <p><b>{{$method}}</b></p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <a href="{{url('/admin/currency')}}" class="text-decoration">
                                <div class="widget-small primary "><i class="icon fa fa-usd fa-3x"></i>
                                    <div class="info">
                                        <h4>Total Currency</h4>
                                        <p><b>{{$currency}}</b></p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <a href="{{url('/admin/gateway')}}" class="text-decoration">
                                <div class="widget-small danger"><i class="icon fa fa-creative-commons fa-3x"></i>
                                    <div class="info">
                                        <h4>Total Gateway</h4>
                                        <p><b>4</b></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Last 7 Days Active Transaction Time</h3>

                <div id="myfirstchart" ></div>
            </div>

        </div>
    </div>

@endsection

@section('script')
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script>
        $(document).ready(function () {
            new Morris.Bar({
                element: 'myfirstchart',
                data: {!! $monthly_play !!},
                xkey: 'y',
                ykeys: ['a'],
                labels: ['Transaction Time']
            });
        });
    </script>
@stop

