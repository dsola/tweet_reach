@extends('layouts.main')

@section('content')
    <div class="container-fluid center-block" style="width:60%">

        <form id="tweet-reach-form" class="form-control form-horizontal" method="post" action="{{ route('tweet.reach.process') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="tweet">Tweet URL</label>
                <input type="url" name="tweet" class="form-control" placeholder="Please, type here the Tweet URL" />
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Calculate Reach">
            </div>
        </form>
    </div>
@endsection