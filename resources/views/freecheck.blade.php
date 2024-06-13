<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Eigen</title>
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

        .tdborder {
            border-right: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
        }

        .card {
            margin: 1%;
        }
    </style>
</head>

{{-- Call te function to translate all the date because there is no build in converter for it --}}
<body onload="translateDate()">
    <div class="alert fixed-top text-capitalize text-center" role="alert" id="message" style="z-index:-1">
    </div>
    <div class="img-links">
        <img src="https://picjj.com/images/2024/05/08/Vs6O1.jpeg" alt="WhatsApp Image 2024 05 08 at 11.45.03">
    </div>

    {{-- Filtering --}}
    @if ($req == 'freeAprove')
    <div class="taskbar d-flex float-start flex-column position-fixed top-0 gap-1 p-1 h-100 d-none d-md-inline-block"
    style="overflow:auto">
    <img src="https://bulldata.nl/wp-content/uploads/2018/08/BullData_Logo_WIT_DEF-600x237.png"
        alt="bulldata">
    <section id="users">
        @if($users->count()>0)
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
        @if ($free->count() > 0 && $free->hasPages())
            <div class="mx-auto mt-2">
                {{ $free->links() }}
            </div>
        @endif
    </section>
    <button class="btn btn-primary w-100 mt-2" onclick="search('freeAprove')">Zoek</button>
    <a class="btn btn-primary w-100 mt-2" href="{{ $org }}">Haal filters weg</a>
</div>
    @elseif($req == 'checkFree')
        {{-- filtering --}}
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
            @if($users->count()>0)
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
            @if ($free->count() > 0 && $free->hasPages())
                <div class="mx-auto mt-2">
                    {{ $free->links() }}
                </div>
            @endif
        </section>
        <button class="btn btn-primary w-100 mt-2" onclick="search('checkFree')">Zoek</button>
        <a class="btn btn-primary w-100 mt-2" href="{{ $org }}">Haal filters weg</a>
    </div>
        {{-- end filtering --}}
    @endif
    
    {{-- Check if there is any data available --}}
    @if ($free->count() > 0)
        {{-- This is for aproving or denying the free --}}
        @if ($req == 'freeAprove')
            <div style="margin-top:7rem"></div>
            @foreach ($free as $fre)
                <div class="card bg-red w-100 w-md-50 mx-lg-auto" id="f{{ $fre->id }}">
                    <div class="card-title">
                        <h1 class="text-uppercase text-center">
                            {{ $fre->user->name}}
                        </h1>
                        <div class="br-bottom"></div>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <label for="reason" class="input-group-text">Reden</label>
                            <div class="form-control" id="reason">{{ $fre->reason }}</div>
                        </div>
                        <div class="input-group">
                            <label for="startDate" class="input-group-text">Begindatum</label>
                            <div class="form-control" id="startDate">
                                {{ date_format(date_create($fre->startDay), 'l d F Y') }}</div>
                        </div>
                        {{-- Check if the free is for a day or not --}}
                        @if ($fre->startTime != null)
                            <div class="input-group">
                                <label for="startTime" class="input-group-text">Begintijd</label>
                                <div class="form-control" id="startTime">{{ $fre->startTime }}</div>
                            </div>
                            <div class="input-group">
                                <label for="endTime" class="input-group-text">Eindtijd</label>
                                <div class="form-control" id="endTime">{{ $fre->endTime }}</div>
                            </div>
                        @else
                            <div class="input-group">
                                <label for="endDate" class="input-group-text">Einddatum</label>
                                <div class="form-control" id="endDate">
                                    {{ date_format(date_create($fre->endDay), 'l d F Y') }}</div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a onclick="aproveOrDeny('disaprove',{{ $fre->id }})"
                            class="btn-red btn w-100 w-md-auto mb-2 mb-md-0">Afkeuren</a>
                        <a onclick="aproveOrDeny('aprove',{{ $fre->id }})"
                            class="btn-success btn float-end w-md-auto w-100">Goedkeuren</a>
                    </div>
                </div>
            @endforeach
            @if ($free->count() > 0 && $free->hasPages())
                <div class="mx-auto d-md-none mt-2">
                    {{ $free->links() }}
                </div>
            @endif
            <script>
                function aproveOrDeny(toDo, id) {
                    // First you are asked if you are sure to do it
                    // After that it makes a api call and let the item disappear
                    // But the item is still presented at the page but not visible
                    if (confirm('Ben je zeker om het te doen')) {
                        api('/api/freeAprove/' + id + "/" + toDo, "GET");
                        $("#f" + id).fadeOut('slow', 'linear', function() {
                            $('#f' + id).remove();
                        });
                    }
                }
            </script>
            {{-- Check if the person wants to see who has when free --}}
        @elseif($req == 'checkFree')
            @foreach ($free as $fre)
                {{-- Check if the free is disaproved --}}
                @if ($fre->state == 3)
                    <div class="card bg-danger w-100 w-md-50 mx-lg-auto" id="{{ $fre->id }}">
                    @else
                        <div class="card bg-success w-100 w-md-50 mx-lg-auto" id="{{ $fre->id }}">
                @endif
                <div class="card-title">
                    <h1 class="text-uppercase text-center">{{ $fre->user->name }}
                    </h1>
                    <div class="br-bottom"></div>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <label for="reason" class="input-group-text">Reden</label>
                        <div class="form-control" id="reason">{{ $fre->reason }}</div>
                    </div>
                    <div class="input-group">
                        <label for="startDate" class="input-group-text">Begindatum</label>
                        <div class="form-control" id="startDate">
                            {{ date_format(date_create($fre->startDay), 'l d F Y') }}</div>
                    </div>
                    {{-- Check if the free is for a day or more --}}
                    @if ($fre->startTime != null)
                        <div class="input-group">
                            <label for="startTime" class="input-group-text">Begintijd</label>
                            <div class="form-control" id="startTime">{{ $fre->startTime }}</div>
                        </div>
                        <div class="input-group">
                            <label for="endTime" class="input-group-text">Eindtijd</label>
                            <div class="form-control" id="endTime">{{ $fre->endTime }}</div>
                        </div>
                    @else
                        <div class="input-group">
                            <label for="endDate" class="input-group-text">Einddatum</label>
                            <div class="form-control" id="endDate">
                                {{ date_format(date_create($fre->endDay), 'l d F Y') }}</div>
                        </div>
                    @endif
                </div>
                </div>
            @endforeach
            @if ($free->count() > 0 && $free->hasPages())
                <div class="mx-auto d-md-none mt-2">
                    {{ $free->links() }}
                </div>
            @endif
        @endif
        {{-- When no person wants to be free --}}
    @else
        <h1 class="text-center text-uppercase mt-5">Er
            zijn geen mensen die vrij vragen.</h1>
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
            var cUsers = checkCheckbox(users);
            var cState = checkCheckbox(state);
            location = url + "/search/" + btoa(JSON.stringify({
                'type': type,
                'sDate': sDate,
                'eDate': eDate,
                'cState': JSON.stringify(cState),
                'users': JSON.stringify(cUsers),
                'href': @json($org)
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
