<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="title">
        <h1>Wonderkind Test</h1>
    </div>

    <div class="container-fluid center-block" style="width:60%">

        <form id="tweet-reach-form" class="form-control form-horizontal" method="post" action="{{ route('tweet.reach.process') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="tweet">Tweet URL</label>
                <input type="url" name="tweet" class="form-control" placeholder="Please, type here the Tweet URL" />
            </div>

            <div class="form-group">
                <input type="submit" class="form-control" value="Calculate Reach">
            </div>
        </form>
    </div>
</body>

</html>
