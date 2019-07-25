@extends('front.layout.master')
@section('style')

@stop
@section('body')
   <div class="row">
       <div class="col-md-8 offset-md-2 col-sm-12">
           <form class="form-horizontal" method="post" action="{{route('edit-profile')}}">

               <div class="jumbotron">
                   @if (count($errors) > 0)
                       <div class="row">
                           <div class="col-md-12">
                               <div class="alert alert-danger alert-dismissible">
                                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                   <h12><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Alert!</h12>
                                   @foreach ($errors->all() as $error)
                                       <li>{{ $error }}</li>
                                   @endforeach
                               </div>
                           </div>
                       </div>
                   @endif


                   @csrf
                   @method('put')
                   <h1 class="tile">Edit Your Profile</h1>

                   <div class="form-group">
                       <strong >Name</strong>
                       <input class="form-control" type="text" name="name"  value="{{$user->name}}" placeholder="Enter Full Name">
                   </div>

                   <div class="form-group">
                       <strong >Email</strong>
                       <input class="form-control" type="email" value="{{$user->email}}" readonly placeholder="Enter your E-mail">
                   </div>

                   <div class="form-group">
                       <strong >Phone (With You country Code)</strong>
                       <input class="form-control" type="tel" name="phone" value="{{$user->phone}}" placeholder="Enter Phone Number">
                   </div>

                   <div class="form-group">
                       <strong >Zip-Code</strong>
                       <input class="form-control" type="text" name="zip_code" value="{{$user->zip_code}}" placeholder="Enter Zip-Code">
                   </div>

                   <div class="form-group">
                       <strong >Address</strong>
                       <input class="form-control" type="text" name="address" value="{{$user->address}}" placeholder="Enter Address">
                   </div>

                   <div class="form-group">
                       <strong >City</strong>
                       <input class="form-control" type="text" name="city" value="{{$user->city}}" placeholder="Enter Your City Name">
                   </div>

                   <div class="form-group">
                       <strong >Country</strong>
                       <input class="form-control" type="text" name="country" value="{{$user->country}}" placeholder="Enter Country Name">
                   </div>





                   <button class="btn btn-lg btn-primary btn-block" type="submit">Update</button>

               </div>
           </form>
       </div>
   </div>
@stop