@extends('admin.layout.master')
@section('css')

    <style>
        button#btn_add {
            margin-bottom: 10px;
        }
    </style>
@stop

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <form class="form-horizontal" method="post" action="{{route('crypto.update', $crypto->id)}}" enctype="multipart/form-data">
                        @csrf
                        {{method_field('put')}}


                            <div class="form-group error">
                                <label for="inputName" class="col-sm-12 control-label bold uppercase"><strong>Name :</strong> </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control has-error bold" id="name" name="name" value="{{$crypto->name}}" placeholder="Crypto Name">
                                </div>
                            </div>
                            <div class="form-group error">
                                <label for="inputName" class="col-sm-12 control-label bold uppercase"><strong>Icon :(PNG Format is Standard)</strong> </label>
                                <div class="col-sm-12">
                                    <img style="width: 80px; height: 80px" src="{{ asset('assets/images/crypto/'.$crypto->icon) }}">
                                </div><br>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control has-error bold" name="icon">
                                </div>

                            </div>

                            <div class="form-group error">
                                <label for="inputName" class="col-sm-12 control-label bold uppercase"><strong>Status</strong> </label>
                                <div class="col-sm-12">
                                    <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                           data-width="100%" type="checkbox" {{$crypto->status ==1? 'checked': ''}} data-on="Active" data-off="Deactive"
                                           name="status">
                                </div>
                            </div>



                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary bold uppercase btn-block"><i class="fa fa-send"></i> Update Crypto</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


@stop