@extends('admin.layout.master')
@section('css')
@stop
@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <form class="form-horizontal" method="post" action="{{route('slider-store')}}" role="form" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">

                                    <div class="form-group">
                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Slider Main Title</strong></label>
                                        <div class="col-md-12">
                                            <input name="title" type="text" class="form-control input-lg" value="{{$slider->title}}" placeholder="Slider  Title" required />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Slider Sub Title</strong></label>
                                        <div class="col-md-12">
                                            <input name="sub_title" type="text" value="{{$slider->sub_title}}" class="form-control input-lg" placeholder="Slider Sub-title" required />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12"><strong style="text-transform: uppercase;">Slider Image</strong></label>
                                        <div class="col-md-12">
                                            <input name="image" type="file" class="form-control input-lg"/>
                                            <img class="center-block" src="{{ asset('assets/images/slider') }}/{{ $slider->image }}" alt="" style="margin-top: 20px;margin-bottom: 10px;width:100%;">
                                            <code><b style="color:red; font-weight: bold;margin-top: 10px">ONE IMAGE ONLY | Image Will Resized to 2100 x 1410 </b></code>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button type="submit" onclick="nicEditors.findEditor('area1').saveContent();" class="btn blue btn-block bold btn-lg uppercase"><i class="fa fa-send"></i> Update Slider</button>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- row -->
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
@stop
@section('script')
    <script>
        $(document).ready(function () {
            $(document).on("click", '.delete_button', function (e) {
                var id = $(this).data('id');
                $(".abir_id").val(id);
            });
        });
    </script>
@stop
