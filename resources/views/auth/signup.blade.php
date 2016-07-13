@extends('template.default')

@section('content')
    <h3>Sign Up</h3>
    <div class="row">
        <div class="col-lg-6">
            <form class="from-vertical" method="post" role="search" action="{{route('auth.signup')}}">
                <div class="form-group {{$errors->has('email') ? ' has-error' : ''}}">
                    <label for="email" class="control-label">Your Email Address</label>
                    <input type="text" name="email" id="email" class="form-control" value="{{Request::old('email') ?: ''}}">
                    @if($errors->has('email'))
                        <span class="help-block">{{$errors->first('email')}}</span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('username') ? ' has-error' : ''}}" >
                    <label for="username" class="control-label">Choose Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="{{Request::old('username') ?: ''}}">
                    @if($errors->has('username'))
                        <span class="help-block">{{$errors->first('username')}}</span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('password') ? ' has-error' : ''}}">
                    <label for="password" class="control-label">Choose Password</label>
                    <input type="password" name="password" id="password" class="form-control" value="">
                    @if($errors->has('password'))
                        <span class="help-block">{{$errors->first('password')}}</span>
                    @endif
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-default">Sign Up</button>
                </div>
                <input type="hidden" name="_token" value="{{Session::token()}}">
            </form>
        </div>
    </div>
@endsection