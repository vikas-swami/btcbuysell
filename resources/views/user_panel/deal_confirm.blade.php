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



            <div class="col-md-8">

                <div class="card">

                    <div class="card-header">
                        Advertisement <span>#{{$add->trans_id}}</span>
                    </div>
                    <form id="uploadDetail" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">

                            <div class="form-group">
                                <strong><i class="fa fa-comment"></i> Send Message to <a style="color: blue" href="{{route('user.profile.view', $add->from_user->username)}}">{{$add->to_user->username}} :</a></strong>
                                <textarea name="message"  class="form-control" id="message" rows="3">{!! old('detail') !!}</textarea>
                            </div>

                            <div class="form-group" id="pranto">
                                <input type="file" class="form-control" name="image">
                                <small class="col-md-12"><i class="fa fa-picture-o"></i> Attach document (PNG , JPG and JPEG files only, take a screenshot if necessary):</small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" id="submit" class="btn btn-secondary">Send</button>
                        </div>
                    </form>

                </div>

                <br>
                <div class="card">

                    <div class="card-header">
                        <strong class="col-md-12">Messages </strong>
                    </div>

                    <div class="card-body">
                        <div id="oww" class="oww">
                            @foreach($add->conversation->reverse() as $data)
                                <div class="col-md-12">
                                    <div class="alert alert-{{$data->type ==1? 'success':'info' }}">
                                        <strong>@if($data->type == 1){{$add->from_user->name}} @else {{Auth::user()->username}} @endif :</strong>
                                        <p><a href="{{asset('assets/images/attach/'.$data->image)}}" download="">@if(isset($data->image)) <img style="width: 180px" src="{{asset('assets/images/attach/'.$data->image)}}"> @endif</a></p>
                                        <p>{!! $data->deal_detail !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-md-4">
                <div class="alert alert-info">
                    <p>You are {{$add->add_type == 1 ? 'buying':'selling' }} {{ round($add->usd_amount/$price,8) }} {{$add->gateway->currency}} with {{$add->amount_to}} {{$add->currency->name}} {{$add->add_type == 2? 'from':'to' }} {{$add->to_user->name}} .</p><br>
                    <p>Transaction status: @if($add->status == 0)
                            <span class="badge badge-warning"> Processing </span>
                        @elseif($add->status == 1)
                            <span class="badge badge-success"> Paid Complete </span>
                        @elseif($add->status == 9)
                            <span class="badge badge-info"> Paid Approval </span>
                        @else
                            <span class="badge badge-danger"> Canceled </span>
                        @endif</p>


                    <div class="row">
                        <div class="col-md-12">

                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header" style="padding: 10px" id="headingOne">
                                        <h5 class="mb-0" >
                                            <button class="btn btn-primary" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Terms of trade with {{$add->to_user->name}}
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                            {!! $add->term_detail !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header" style="padding: 10px" id="headingTwo">
                                        <h5 class="mb-0">
                                            <button class="btn btn-primary collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Payment Detail with {{$add->to_user->name}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                        <div class="card-body">
                                            {!! $add->payment_detail !!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                @if($add->status == 0)
                    <div class="alert alert-success text-center">
                        <strong>Make Sure You Paid, Then Click Butoon "Paid"</strong>
                        <br>
                        <br>
                        <button type="button" data-toggle="modal" data-target="#paidModal" class="btn btn-success btn-block" >Paid </button>
                    </div>
                @endif

                @if($add->status == 0 || $add->status == 9)
                    <div class="alert alert-danger text-center">
                        <strong>If you do not want to make any deal with {{$add->to_user->name}}, then click "Cencel Request".</strong>
                        <br>
                        <br>
                        <p><button type="button" data-toggle="modal" data-target="#cancelModal" class="btn btn-danger btn-block" >Cancel Request</button></p><br>
                    </div>
                @else

                @endif
            </div>



    </div>


    <div id="paidModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirm Paid<strong> from {{$add->to_user->name}} ?</strong></h4>
                </div>

                <form method="post" action="{{route('confirm.paid.reverse')}}">
                    @csrf


                    <div class="modal-footer">
                        <button type="submit" name="status" value="{{$add->id}}" class="btn btn-primary pull-right">Paid</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <div id="cancelModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: red">Confirm Cancel Request</h4>
                </div>

                <form method="post" action="{{route('confirm.cancel.reverse')}}">
                    @csrf

                    <div class="modal-footer">
                        <button type="submit" name="status" value="{{$add->id}}" class="btn btn-primary pull-right">Confirm Cancel</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop

@section('script')
    <script>

        $(window).ready( function() {
            setInterval( function() {
                $("#oww").load(location.href + " #oww");
            }, 500 );
        });

        $(document).ready(function () {

            $('#submit').click(function (e) {

                var preLoader = $("#preloader").css('display','block');
                preLoader.fadeOut(600);

                e.preventDefault();
                var id = "{{$add->id}}";
                var message = $('#message').val();

                if (message == ''){
                    alert('message field is required');
                }
                var profileForm = $('#uploadDetail')[0];
                var formData = new FormData(profileForm);

                formData.append('id', id);
                formData.append('message', message);
                formData.append('_token', "{{csrf_token()}}");


                $.ajax({
                    type : "POST",
                    url : "{{route('send.message.deal')}}",
                    data : formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success:function (data) {

                        $("#pranto").load(location.href + " #pranto");
                        $('#message').val(' ');
                    }
                })

                setTimeout(function () {
                    $("#oww").load(location.href + " #oww");
                }, 3000)
            });

        });
    </script>
@stop