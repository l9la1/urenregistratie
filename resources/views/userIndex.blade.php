<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Main</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/png" href="https://pbs.twimg.com/media/Dsb8CVYWwAAyTlW.jpg">

    <style>
        body {
            overflow-y: hidden;
        }

        .btn-danger {
            background-color: #EA212D;
            border-color: #EA212D;
        }

        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 155px;
            background-color: #EEEEEE;
        }

        .logo {
            padding-top: 40px;
            width: 100%;
        }

        .logo img {

            width: 155px;
            height: 70px;


        }

        nav {
            margin-top: -30px;
        }

        .me-2 {

            margin-left: 0.5rem !important;
        }
    </style>
</head>

<body>


    <nav class="float-start d-flex flex-column sidebar" style="background-color: #F7F7F7;" id="nav">
        <div class="logo">
            <img src="https://picjj.com/images/2024/05/08/Ve4po.png" alt="Schermafbeelding 2024 05 08 141956">
        </div>
            <button onclick="setIframe('{{ route('addHours', ['req' => 'addHour']) }}')"
                class="btn btn-danger ms-1 mt-2 mb-2 me-2">Uren toevoegen</button>

            <button onclick="setIframe('{{ route('addHours', ['req' => 'free']) }}')"
                class="btn btn-danger ms-1 mb-2 me-2">Verlof aanvragen</button>
            <div class="btn-group dropstart">
                <button type="button" class="btn btn-danger ms-1 mb-2 me-2 dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">Statistieken</button>
                <ul class="dropdown-menu ms-2 text-light p-1">
                    <li><button onclick="setIframe('{{ route('addHours', ['req' => 'amountOfHours']) }}')"
                            class="btn btn-danger w-100 h-100 mb-2">Uren</button></li>
                    <li><button onclick="setIframe('{{ route('addHours', ['req' => 'controlFree']) }}')"
                            class="btn btn-danger mb-2 w-100">Verlof</button></li>
                    <li><button onclick="setIframe('{{ route('addHours', ['req' => 'statistics']) }}')"
                            class="btn btn-danger w-100 h-100 mb-2">Statistieken</button></li>
                </ul>
            </div>
            <a href="{{ route('downloadn')}}" class="btn btn-danger ms-1 mb-2 me-2">Handleiding</a>
            <a href="{{ route('logout') }}" class="btn btn-danger me-2"
                onclick="return confirm('Ben je zeker ervan om uit te loggen')"
                style="bottom:25px;position:absolute;width:140px;">Log uit</a>
    </nav>
    <iframe title="shower" style="height:100vh; width:calc(100% - 157px); display:none;" id="load"
        onload="$(this).fadeIn();" src="{{ route('welcome') }}">

    </iframe>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
        integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function setIframe(src) {
            $("#load").hide();
            $("#load").attr("src", src);
        }
    </script>
</body>

</html>
