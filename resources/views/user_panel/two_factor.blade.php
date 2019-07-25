@extends('front.layout.master')
@section('style')

@stop
@section('body')

    <div class="row padding-pranto-top padding-pranto-bottom">
        @if (count($errors) > 0)
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="col-md-6">
            @if(Auth::user()->tauth == '1')
                <div class="card">
                    <div class="card-header">
                        <h4 class="panel-title">Two Factor Authenticator</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" value="{{$prevcode}}" class="form-control input-lg" id="code" readonly>
                                <span class="input-group-addon btn btn-success" id="copybtn">Copy</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <img src="{{$prevqr}}">
                        </div>
                        <button type="button" class="btn btn-block btn-lg btn-danger" data-toggle="modal" data-target="#disableModal">Disable Two Factor Authenticator</button>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <h4 class="panel-title">Two Factor Authenticator</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="key" value="{{$secret}}" class="form-control input-lg" id="code" readonly>
                                <span class="input-group-addon btn btn-success" id="copybtn">Copy</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <img src="{{$qrCodeUrl}}">
                        </div>
                        <button type="button" class="btn btn-block btn-lg btn-primary small-font-size-in-mobile-device" data-toggle="modal" data-target="#enableModal">Enable Two Factor Authenticator</button>
                    </div>
                </div>
            @endif


        </div>

        <div class="col-md-6 margin-top-pranto">
            <div class="card">
                <div class="card-header">
                    <h4 class="panel-title">Google Authenticator</h4>
                </div>
                <div class="card-body text-center">
                    <h5 style="text-transform: capitalize;">Use Google Authenticator to Scan the QR code  or use the code</h5><hr/>
                    <p>Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.</p>
                    <a class="btn btn-info" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">DOWNLOAD APP</a>
                </div>
            </div>
        </div>

    </div>





    <!--Enable Modal -->
    <div id="enableModal" class="modal fade" role="dialog">

        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Verify Your OTP</h4>
                </div>

                <form method="post" action="{{route('go2fa.create')}}">
                    @csrf

                    <div class="modal-body">
                        {{csrf_field()}}

                        <div class="form-group">
                            <input type="hidden" name="key" value="{{$secret}}">
                            <input type="text" class="form-control" name="code" placeholder="Enter Google Authenticator Code">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary pull-right">Verify</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Disable Modal -->
    <div id="disableModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Verify Your OTP to Disable</h4>
                </div>
                <div class="modal-body">
                    <form action="{{route('disable.2fa')}}" method="POST">
                        {{csrf_field()}}
                        <div class="form-group">
                            <input type="text" class="form-control" name="code" placeholder="Enter Google Authenticator Code">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-success btn-block">Verify</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('script')
    <script type="text/javascript">
        document.getElementById("copybtn").onclick = function()
        {
            document.getElementById('code').select();
            document.execCommand('copy');
        }
    </script>
@stop