<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.4.0/css/bulma.min.css">
    <title>Vacant WIP Notification</title>
</head>
<body>

<div class="columns is-mobile">
    @foreach($data as $vacant)
        <div class="column is-three-quarters is-offset-one-quarter">
            <div class="box">
                <article class="media">
                    <div class="media-left">
                        <figure class="image is-64x64">
                            <img src="http://bulma.io/images/placeholders/128x128.png" alt="Image">
                        </figure>
                    </div>
                    <div class="media-content">
                        <div class="content">
                            <p>
                                <h3>{{ $vacant['store_name'] }}</h3>
                                <br>
                                 First Date {{ $vacant['effective_date'] }}
                                    <br />
                                 Vacant Reson {{ $vacant['reason'] }}
                                    <br />
                                 Vacant head count {{ $vacant['head_count'] }}
                            </p>
                        </div>
                        <nav class="level is-mobile">
                            <div class="level-left">
                                <a class="level-item">
                                    <span>Open in Badev.id</span>
                                </a>
                            </div>
                        </nav>
                    </div>
                </article>
            </div>
        </div>
    @endforeach

</div>
</body>
</html>