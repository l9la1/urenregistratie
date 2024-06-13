<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welkom</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        .fancy
        {
            animation:fancy 5s infinite alternate;
            left:50%;
            top:50%;
            position: absolute;
            transform: translate(-50%,50%);
        }

        @keyframes fancy
        {
            0%
            {
                color:#000;
            }
            25%
            {
                color:#f00;
            }
            50%
            {
                color:#0f0;
            }
            100%
            {
                color:#00f;
            }
        }
    </style>
</head>
<body>
    <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fbulldata.nl%2Fwp-content%2Fuploads%2F2018%2F08%2FBullData_Logo_DEF.png&f=1&nofb=1&ipt=0910d259a5cd9473b0b702500ab064cd1bdc596d1d53e14ce312c0b046dbca39&ipo=images"
    alt="bulldata" style="width:15rem; position:absolute; top:0;">
    <div class="text-center text-uppercase fancy">
        <h1>Welkom {{Auth::user()->name}}</h1>
    </div>
</body>
</html>