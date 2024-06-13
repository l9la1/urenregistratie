<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Beheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
    body {
        overflow-y: hidden;
    }

    .navbar {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: 190px;
        background-color: #EEEEEE;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    .navbar .btn-group {
        width: 100%;
    }

    .navbar .btn {
        background-color: #EA212D;
        color: white;
        width: calc(100% - 10px);
        margin: 5px;
    }

    .navbar .btn:hover {
        background-color: darkred;
        color: white;
    }

    .logo img {

        width: 190px;
        /* Pas de breedte aan zoals gewenst */

        /* Zorgt ervoor dat de hoogte automatisch wordt aangepast om de aspect ratio te behouden */
    }

    .logout-btn {

        margin-bottom: 200px;
        /* Voeg 20px marge aan de onderkant toe */
    }

    .dropdown-menu {
        background-color: transparent;
        /* Transparante achtergrond */
        border: none;
        /* Geen rand */
        box-shadow: none;
        /* Geen schaduw */
        color: white;
    }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="logo">
            <img src="https://picjj.com/images/2024/05/08/Ve4po.png" alt="Schermafbeelding 2024 05 08 141956">
        </div>
        <div class="btn-group dropstart">
            <button type="button" class="btn btn-red dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">Uren</button>
            <ul class="dropdown-menu p-1">
                <li> <button type="button" class="btn btn-red"
                    onclick="setIframe('{{ route('urencheck',['req'=>'urengoedkeuren']) }}')">Uren
                    nakijken</button></li>
                <li><button onclick="setIframe('{{ route('urencheck', ['req' => 'checkuren']) }}')"
                        class="btn btn-redlight w-100 h-100 mb-2">Nagekeken uren</button></li>
            </ul>
        </div>
        <div class="btn-group dropstart">
            <button type="button" class="btn btn-red dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">Verlof</button>
            <ul class="dropdown-menu p-1">
                <li><button onclick="setIframe('{{ route('freecheck', ['req' => 'freeAprove']) }}')"
                        class="btn btn-redlight w-100 h-100 mb-2">Verlof uren nakijken</button></li>
                <li><button onclick="setIframe('{{ route('freecheck', ['req' => 'checkFree']) }}')"
                        class="btn btn-redlight w-100 h-100 mb-2">Nagekeken verlof</button></li>
            </ul>
        </div>
        <div class="btn-group dropstart">
            <button type="button" class="btn btn-red dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">Gebruikers beheren</button>
            <ul class="dropdown-menu p-1">
                <li><button onclick="setIframe('{{ route('usercontrol', ['req' => 'showAndEdit']) }}')"
                        class="btn btn-redlight w-100 h-100 mb-2">Bekijk en pas gebruiker aan</button></li>
                <li><button onclick="setIframe('{{ route('usercontrol', ['req' => 'addNew']) }}')"
                        class="btn btn-redlight w-100 h-100 mb-2">Voeg gebruiker toe</button></li>
                <li><button onclick="setIframe('{{ route('usercontrol', ['req' => 'own']) }}')"
                        class="btn btn-redlight w-100 h-100 mb-2">Eigen account</button></li>
            </ul>
        </div>
        <div class="btn-group dropstart">
            <button type="button" class="btn btn-red dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">Overige</button>
            <ul class="dropdown-menu p-1">
        <li><button onclick="setIframe('{{ route('astatistic') }}')" class="btn btn-red">Statistieken</button></li>
        <li><a href="{{ route('downloadn')}}" class="btn btn-red">Handleiding normale gebruiker</a></li>
        <li><a href="{{ route('downloada')}}" class="btn btn-red">Handleiding beheerder</a></li>
            </ul>
        </div>
        <a href="{{ route('logout') }}" class="btn btn-red logout-btn"
            style="bottom:25px;position:absolute;width:180px;"
            onclick="return confirm('Ben je zeker ervan om uit te loggen')">Log uit</a>
    </nav>

    <iframe title="shower" style="height:100vh; width:calc(100% - 190px); display:none;" id="load"
        onload="$(this).fadeIn()" src="{{ route('welcome') }}"></iframe>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
        integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
    // This is for setting the src of the iframe to switch pages
    function setIframe(src) {
        $("#load").hide();
        $("#load").attr("src", src);
    }
    </script>
</body>

</html>