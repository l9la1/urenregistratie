<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <style>
    .logo {
        width: 400px;
        /* Aanpassen aan gewenste grootte */
        margin: 0 auto;
        margin-top: 50px;
        /* Afbeelding centreren */
        display: block;
        /* Zorg ervoor dat het een blok is om margin:auto te laten werken */
    }

    .card {
        max-width: 45%;
        /* Verklein de maximale breedte van de kaart */
        margin: 0 auto;
        display: flex;
        top: 75px;
        left: 450px;
    }

    .border-end {
        border-width: 2px !important;
        border-color: #555 !important;
    }

    .form {
        margin-top: 50px;

    }

    .input {
        width: 100%;
        border: none;
        background-color: transparent;
        border-bottom: 1px solid #500;
        margin-bottom: 10px;
        /* Extra ruimte tussen inputvelden */
    }

    h1 {
        border-bottom: 2px solid #500;
        color: rgba(0, 0, 0, 0.9);
        margin-bottom: 25px;

    }

    .btn-login {
        width: 100%;
    }
    </style>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="card bg-red rounded m-4 centered">
        <div class="row m-1">
            <div class="col-12">
                <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fbulldata.nl%2Fwp-content%2Fuploads%2F2018%2F08%2FBullData_Logo_DEF.png&f=1&nofb=1&ipt=0910d259a5cd9473b0b702500ab064cd1bdc596d1d53e14ce312c0b046dbca39&ipo=images"
                    alt="bulldata" class="img-fluid logo">
            </div>
            <div class="col-12">
                <form action="{{ route('login') }}" method="post" class="form">
                    @csrf
                    <div class="login">
                        <h1 class="text-center text-uppercase">Inloggen</h1>
                        @if ($errors->any())
                        @foreach ($errors->all() as $error)
                        <div class="alert alert-warning" role="alert">
                            {{ $error }}
                        </div>
                        @endforeach
                        @endif
                        <label for="email">Email</label><br />
                        <input type="email" name="email" id="email" class="input mb-2"><br />
                        <label for="password">Wachtwoord</label><br />
                        <input type="password" name="password" id="password" class="input">
                        <input type="submit" value="Log in" class="btn btn-red mt-2 btn-login"
                            style="margin-bottom: 19px; font-size: 25px;">

                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>