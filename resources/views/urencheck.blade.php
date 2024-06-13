<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Uren goedkeuren</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>
    <style>
        .tdborder {
            border-right: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
        }

        .card {
            margin: 1%;
        } 
    </style>
</head>

<body onload="translateDate()">
    <div class="alert fixed-top text-capitalize text-center" role="alert" id="message" style="z-index:-1">
    </div>
    <div class="img-links">
        <img src="https://picjj.com/images/2024/05/08/Vs6O1.jpeg" alt="WhatsApp Image 2024 05 08 at 11.45.03">
    </div>

    {{-- Check if the persons wants to check the hours of the employees --}}
    @if ($req == 'urengoedkeuren')
        <div class="taskbar d-flex float-start flex-column position-fixed top-0 gap-1 p-1 h-100 d-none d-md-inline-block"
            style="overflow:auto">
            <img src="https://bulldata.nl/wp-content/uploads/2018/08/BullData_Logo_WIT_DEF-600x237.png"
                alt="bulldata">
            <section id="users">
                @if ($users->count() > 0)
                    <h2 class="border-bottom text-center text-uppercase">Gebruikers</h2>
                    <div style="max-height:250px; overflow-y:auto;">
                        @foreach ($users as $usr)
                            <div class="form-check">
                                <label for="{{ $usr->id }}" class="form-check-label">{{ $usr->name }}</label>
                                @if (isset($cUser) && in_array($usr->id, $cUser))
                                    <input type="checkbox" name="{{ $usr->id }}" id="{{ $usr->id }}"
                                        class="form-check-input" value="{{ $usr->id }}" checked>
                                @else
                                    <input type="checkbox" name="{{ $usr->id }}" id="{{ $usr->id }}"
                                        class="form-check-input" value="{{ $usr->id }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
            <section id="cat">
                <h2 class="border-bottom text-center text-uppercase text-light">categorieën</h2>
                @foreach ($cat as $ct)
                    <div class="form-check">
                        @if (isset($sCat) && in_array($ct->id, $sCat))
                            <label class="form-check-label"
                                for="c{{ $ct->id }}">{{ $ct->category_name }}</label>
                            <input id="c{{ $ct->id }}" class="form-check-input" type="checkbox"
                                value="{{ $ct->id }}" checked />
                        @else
                            <label class="form-check-label"
                                for="c{{ $ct->id }}">{{ $ct->category_name }}</label>
                            <input id="c{{ $ct->id }}" class="form-check-input" type="checkbox"
                                value="{{ $ct->id }}" />
                        @endif
                    </div>
                @endforeach
            </section>
            <section id="dates">
                <h2 class="border-bottom text-center text-uppercase">Datums</h2>
                <div class="d-flex flex-column">
                    <label for="sDate">Van: </label>
                    @if (isset($sDate))
                        <input type="date" name="sDate" id="sDate" value={{ $sDate }}>
                    @else
                        <input type="date" name="sDate" id="sDate">
                    @endif
                    <label for="eDate">Tot: </label>
                    @if (isset($eDate))
                        <input type="date" name="eDate" id="eDate" value={{ $eDate }}>
                    @else
                        <input type="date" name="eDate" id="eDate">
                    @endif
                </div>
            </section>
            <section>
                @if ($houresTocheck->count() > 0 && $houresTocheck->hasPages())
                    <div class="mx-auto mt-2">
                        {{ $houresTocheck->links() }}
                    </div>
                @endif
            </section>

            <button class="btn btn-primary w-100 mt-2" onclick="search('urengoedkeuren')">Zoek</button>
            <a class="btn btn-primary w-100 mt-2" href="{{ $org }}">Haal filters weg</a>
        </div>
        <div class="mt-5" id="holder">
            {{-- Check if there any hours to be checked --}}
            @if ($houresTocheck->count() > 0)
                @foreach ($houresTocheck as $hour)
                    <div class="card bg-red w-100 w-md-50 mx-lg-auto mb-3" id='f{{ $hour->id }}'>
                        <div class="card-title text-center text-uppercase">
                            <h3>{{ $hour->user->name }}</h3>
                            <div class="br-bottom"></div>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <div class="input-group-text">Begintijd:</div>
                                <div class="form-control">{{ $hour->startTime }}</div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-text">Pauze:</div>
                                <div class="form-control">{{ $hour->pause }}</div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-text">Eindtijd:</div>
                                <div class="form-control">{{ $hour->endTime }}</div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-text">Dag:</div>
                                <div class="form-control">{{ date_format(date_create($hour->day), 'l d F Y') }}</div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-text">Categorie:</div>
                                <div class="form-control">{{ $hour->cat->category_name }}</div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-text">Uitleg:</div>
                                <div class="form-control">{{ $hour->reason }}</div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a onclick="updateHour('{{ $hour->id }}','afkeuren')"
                                class="btn btn-danger w-100 w-md-auto mb-2 mb-md-0">Afkeuren</a>
                            <a onclick="updateHour('{{ $hour->id }}','goedkeuren')"
                                class="btn btn-success float-end w-100 w-md-auto">Goedkeuren</a>
                        </div>
                    </div>
        </div>
    @endforeach
@else
    <h1 class="text-capitalize text-center">Er zijn geen uren te controleren</h1>
    @endif
    <script>
        function updateHour(id, command) {
            // Ask first if you sure to do it if so hide the element
            if (confirm("Ben je zeker om het aan te passen")) {
                if (command == "afkeuren") // Check if it has to approve or disapprove
                    api('/api/houra/' + id, 'GET');
                else if (command == "goedkeuren")
                    api("/api/hourc/" + id, "GET");
                $("#f" + id).fadeOut("slow","linear",function()
            {
                $("#f" + id).remove();
            });
            }
        }
    </script>
    {{-- This is for when the person wants to see all the hours worked by the employees --}}
@elseif($req == 'checkuren')
    <div class="taskbar d-flex float-start flex-column position-fixed top-0 gap-1 p-1 h-100 d-none d-md-inline-block"
        style="overflow:auto">
        <img src="https://bulldata.nl/wp-content/uploads/2018/08/BullData_Logo_WIT_DEF-600x237.png"
            alt="bulldata">
        <section id="state">
            <h2 class="border-bottom text-center text-uppercase text-light">status</h2>
            <div class="form-check">
                <label for="status2" class="form-check-label">nagekeken</label>
                @if (isset($state) && in_array('2', $state))
                    <input type="checkbox" value=2 id="status2" class="form-check-input" checked />
                @else
                    <input type="checkbox" value=2 id="status2" class="form-check-input" />
                @endif
            </div>
            <div class="form-check">
                <label for="status3" class="form-check-label">afgekeurd</label>
                @if (isset($state) && in_array('3', $state))
                    <input type="checkbox" value=3 id="status3" class="form-check-input" checked />
                @else
                    <input type="checkbox" value=3 id="status3" class="form-check-input" />
                @endif
            </div>
        </section>
        <section id="users">
            @if ($users->count() > 0)
                <h2 class="border-bottom text-center text-uppercase">Gebruikers</h2>
                <div style="max-height:250px; overflow-y:auto;">
                    @foreach ($users as $usr)
                        <div class="form-check">
                            <label for="{{ $usr->id }}" class="form-check-label">{{ $usr->name }}</label>
                            @if (isset($cUser) && in_array($usr->id, $cUser))
                                <input type="checkbox" name="{{ $usr->id }}" id="{{ $usr->id }}"
                                    class="form-check-input" value="{{ $usr->id }}" checked>
                            @else
                                <input type="checkbox" name="{{ $usr->id }}" id="{{ $usr->id }}"
                                    class="form-check-input" value="{{ $usr->id }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
        <section id="cat">
            <h2 class="border-bottom text-center text-uppercase text-light">categorieën</h2>
            @foreach ($cat as $ct)
                <div class="form-check">
                    @if (isset($sCat) && in_array($ct->id, $sCat))
                        <label class="form-check-label" for="c{{ $ct->id }}">{{ $ct->category_name }}</label>
                        <input id="c{{ $ct->id }}" class="form-check-input" type="checkbox"
                            value="{{ $ct->id }}" checked />
                    @else
                        <label class="form-check-label" for="c{{ $ct->id }}">{{ $ct->category_name }}</label>
                        <input id="c{{ $ct->id }}" class="form-check-input" type="checkbox"
                            value="{{ $ct->id }}" />
                    @endif
                </div>
            @endforeach
        </section>
        <section id="dates">
            <h2 class="border-bottom text-center text-uppercase">Datums</h2>
            <div class="d-flex flex-column">
                <label for="sDate">Van: </label>
                @if (isset($sDate))
                    <input type="date" name="sDate" id="sDate" value={{ $sDate }}>
                @else
                    <input type="date" name="sDate" id="sDate">
                @endif
                <label for="eDate">Tot: </label>
                @if (isset($eDate))
                    <input type="date" name="eDate" id="eDate" value={{ $eDate }}>
                @else
                    <input type="date" name="eDate" id="eDate">
                @endif
            </div>
        </section>
        <section>
            @if ($houresChecked->count() > 0 && $houresChecked->hasPages())
                <div class="mx-auto mt-2">
                    {{ $houresChecked->links() }}
                </div>
            @endif
        </section>
        <button class="btn btn-primary w-100 mt-2" onclick="search('checkuren')">Zoek</button>
        <a class="btn btn-primary w-100 mt-2" href="{{ $org }}">Haal filters weg</a>
    </div>
    @if ($houresChecked->count() > 0)
        <div id="holder" class="mt-5">
            @foreach ($houresChecked as $hour)
                @if ($hour->state == 3)
                    <div class="card  w-100 w-md-50 mx-lg-auto bg-danger mb-3" id={{ $hour->id }}>
                    @else
                        <div class="card w-100 w-md-50 mx-lg-auto bg-success mb-3" id={{ $hour->id }}>
                @endif
                <div class="card-title text-center text-uppercase">
                    <h3>{{ $hour->user->name }}</h3>
                    <hr />
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <div class="input-group-text">Begintijd:</div>
                        <div class="form-control">{{ $hour->startTime }}</div>
                    </div>
                    <div class="input-group">
                        <div class="input-group-text">Pauze:</div>
                        <div class="form-control">{{ $hour->pause }}</div>
                    </div>
                    <div class="input-group">
                        <div class="input-group-text">Eindtijd:</div>
                        <div class="form-control">{{ $hour->endTime }}</div>
                    </div>
                    <div class="input-group">
                        <div class="input-group-text">Dag:</div>
                        <div class="form-control">{{ date_format(date_create($hour->day), 'l d F Y') }}</div>
                    </div>
                    <div class="input-group">
                        <div class="input-group-text">Categorie:</div>
                        <div class="form-control">{{ $hour->cat->category_name }}</div>
                    </div>
                    <div class="input-group">
                        <div class="input-group-text">Uitleg:</div>
                        <div class="form-control">{{ $hour->reason }}</div>
                    </div>

                </div>
        </div>
    @endforeach
    </div>
    @if ($houresChecked->count() > 0 && $houresChecked->hasPages())
        <div class="mx-auto d-md-none mt-2">
            {{ $houresChecked->links() }}
        </div>
    @endif
@else
    <h1 class="text-capitalize text-center">Er zijn nog geen uren gecontroleerd</h1>
    @endif
    @endif


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
        integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function search(type) {
            // This is for making the url self to send a api call 
            // I had to made it so complicated because when i do it in a form the data is send 
            // before any data is filled in
            // And when i do it with post i get trouble with my pagination
            var url = location.protocol + '//' + location.host;
            var users = $("#users").find('.form-check-input');
            var state = $("#state").find('.form-check-input');
            var sDate = $("#sDate").val();
            var eDate = $("#eDate").val();
            var cat = checkCheckbox($("#cat").find(".form-check-input"));
            var cUsers = checkCheckbox(users);
            var cState = checkCheckbox(state);
            console.log(cat);
            location = url + "/search/" + btoa(JSON.stringify({
                'type': type,
                'sDate': sDate,
                'eDate': eDate,
                'cState': JSON.stringify(cState),
                'users': JSON.stringify(cUsers),
                'href': @json($org),
                'cat': JSON.stringify(cat)
            }));
        }

        // This checks if the checkboxes are checked
        function checkCheckbox(input) {
            var arr = [];
            for (var i = 0; i < input.length; i++) {
                if (input[i].checked) {
                    arr.push(input[i].value);
                }
            }
            return arr;
        }
    </script>
</body>

</html>
