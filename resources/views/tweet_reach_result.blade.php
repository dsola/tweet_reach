@extends('layouts.main')

@section('content')
<div class="container-fluid center-block" style="width:60%">
    The total reach of this tweet is: <b>{{ $reach }}</b>
    <div class="form-group">
        <a href="{{ route('tweet.reach') }}" class="btn btn-primary">Try another one!</a>
    </div>
</div>
@endsection