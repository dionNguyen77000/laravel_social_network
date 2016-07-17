

@extends('template.default')

@section('content')
    <div class="row">
        <div class="col-lg-5">
            {{--User information and statuses--}}
            @include('user.partials.userblock')
            <hr>
        </div>
        <div class="col-lg-4 col-lg-offset-3">
            {{--Send friend request, accept request session for current login user--}}
           @if(Auth::user()->hasFriendRequestPending($user)) {{--check if $user has pending request from login user--}}
               <p>Waiting for {{ $user->getNameOrUsername() }} to accept your request.
               </p>
           @elseif (Auth::user()->hasFriendRequestRecieved($user)){{--check if i have any friednd request from $user--}}
                <a href="{{route('friend.accept', ['username' => $user -> username])}}" class="btn btn-primary">Accept friend request</a>
            @elseif (Auth::user()->isFriendsWith($user)) {{--check if login user and $user are friends--}}
               <p>You and {{$user->getNameOrUsername()}} are friends.</p>
           @elseif (Auth::user()->id !== $user->id)
                <a href="{{route('friend.add', ['username' => $user -> username])}}" class="btn btn-primary">Add as friend</a>
           @endif

            {{--List of current friends of login user session--}}
            <h3> {{$user->getFirstNameOrUsername() }}'s friends. </h3>

            @if(!$user->friends()->count())
                <p>{{$user->getFirstNameOrUsername()}} has no friends. </p>
            @else
                @foreach($user->friends() as $user)
                    @include('user/partials/userblock')
                @endforeach
            @endif
        </div>
    </div>
@endsection