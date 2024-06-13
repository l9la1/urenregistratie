<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>

    <style>
        .input-group-text {
            width: 100px;
        }
    </style>
</head>

<body>
    <div class="alert fixed-top text-capitalize text-center" role="alert" id="message" style="z-index:-1">
    </div>
    <div class="img-links">
        <img src="https://picjj.com/images/2024/05/08/Vs6O1.jpeg" alt="WhatsApp Image 2024 05 08 at 11.45.03">
    </div>
    {{-- This is for to edit employees --}}
    @if ($req == 'showAndEdit')
        @if ($users->count() == 0)
            <h1 class="text-capitalize text-center">Er zijn geen werknemers om te bewerken</h1>
        @else
            <div class="taskbar d-md-flex float-start flex-column position-fixed top-0 gap-1 p-1 h-100 d-none"
                style="overflow:auto">
                <img src="https://bulldata.nl/wp-content/uploads/2018/08/BullData_Logo_WIT_DEF-600x237.png"
                    alt="bulldata" style="width:10rem;">
                <section id="users">
                    @if ($aUser != null && $aUser->count() > 0)
                        <h2 class="border-bottom text-center text-uppercase text-light">Gebruikers</h2>
                        <div style="max-height:250px; overflow-y:auto;">

                            @foreach ($aUser as $usr)
                                <div class="form-check" id="f{{ $usr->id }}">
                                    <label for="{{ $usr->id }}"
                                        class="form-check-label">{{ $usr->name }}</label>
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
                {{-- Check for pagination --}}
                @if ($users->count() > 0 && $users->hasPages())
                    <section>
                        <div class="mx-auto">
                            {{ $users->links() }}
                        </div>
                    </section>
                @endif
                @if ($aUser != null && $aUser->count() > 0)
                    <button class="btn btn-primary w-100" onclick="search('showAndEdit')">Zoek</button>
                    <a class="btn btn-primary w-100 mt-2" href="{{ $org }}">Haal filters weg</a>
                @endif
            </div>
            @foreach ($users as $user)
                <div class="card w-100 w-md-50 mx-md-auto mt-5 bg-red" id="u{{ $user->id }}">
                    <div class="card-title">
                        <h1 class="text-center text-uppercase">Bewerk <b>{{ $user->name }}</b></h1>
                        <div class="br-bottom"></div>
                    </div>
                    <div class="card-body">
                        <form onsubmit="updateUsers(event)">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <div class="input-group">
                                <label for="u{{ $user->id }}" class="input-group-text">Naam</label>
                                <input type="text" name="name" id="u{{ $user->id }}"
                                    value="{{ $user->name }}" class="form-control">
                            </div>
                            <div class="input-group">
                                <label for="e{{ $user->id }}" class="input-group-text">Email</label>
                                <input type="email" name="email" id="e{{ $user->id }}"
                                    value="{{ $user->email }}"class="form-control">
                            </div>
                            <div class="input-group">
                                <label for="r{{ $user->id }}" class="input-group-text">Rol</label>
                                <select name="role" id="r{{ $user->id }}" class="form-control">
                                    @foreach ($roles as $role)
                                        @if ($role->id == $user->role)
                                            <option value="{{ $role->id }}" selected>{{ $role->role_name }}
                                            </option>
                                        @else
                                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a onclick="removeUser({{ $user->id }})"
                                class="btn btn-danger w-100 w-md-auto mb-2 mb-md-0">Verwijder
                                account</a>
                            <a onclick="newPass({{ $user->id }})"
                                class="btn btn-success w-100 w-md-auto mb-2 mb-md-0">Nieuw wachtwoord</a>
                            <input type="submit" value="Pas aan" class="w-100 w-md-auto btn btn-success">
                        </div>
                    </div>
                </div>
                </form>
            @endforeach
            @if ($users->count() > 0 && $users->hasPages())
                <div class="mx-auto d-md-none mt-2">
                    {{ $users->links() }}
                </div>
            @endif
            <script>
                function removeUser(id) {
                    // Ask if you are sure to do it
                    // If so then it will be adapted
                    if (confirm("Ben je zeker om het aan te passen")) {
                        api('/api/userd/' + id, "GET");
                        $("#u" + id).fadeOut("slow", "linear", function() {
                            $("#u" + id).remove();
                        });
                        $("#f" + id).fadeOut("slow", "linear", function() {
                            $("#f" + id).remove();
                        });
                    }
                }

                function updateUsers(event) {
                    // It is a bit the same as removeUser()
                    if (confirm("Ben je zeker om het aan te passen")) {
                        event.preventDefault();
                        var formData = new FormData(event.target);
                        api("/api/useru", "POST", formData);
                    }
                }

                function newPass(id) {
                    if (confirm("Ben je zeker om een nieuw wachtwoord aan te maken")) {
                        $("#message").removeClass("text-capitalize");
                        api('/api/uPass/' + id, "GET", null, 5000, function() {
                            $("#message").addClass("text-capitalize");
                            $("#name,#email").val("");
                        });
                    }
                }
            </script>
        @endif

        {{-- This is for adding new users --}}
    @elseif($req == 'addNew')
        <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fbulldata.nl%2Fwp-content%2Fuploads%2F2018%2F08%2FBullData_Logo_DEF.png&f=1&nofb=1&ipt=0910d259a5cd9473b0b702500ab064cd1bdc596d1d53e14ce312c0b046dbca39&ipo=images"
            alt="bulldata" style="width:15rem; position:absolute; top:0;">
        <form onsubmit="addNewUser(event)">
            @csrf
            <div class="card w-50 mx-auto bg-red center-small-top" style="margin-top:15%;">
                <h3 class="card-title text-uppercase text-center">Nieuwe gebruiker toevoegen</h3>
                <div class="br-bottom"></div>
                <div class="card-body">
                    <div class="input-group">
                        <label for="name" class="input-group-text">Naam</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    <div class="input-group">
                        <label for="email" class="input-group-text">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="input-group">
                        <label for="role" class="input-group-text">Rol</label>
                        <select name="role" id="role" class="form-control">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="Aanmaken" class="btn btn-red float-end">

                </div>
            </div>
        </form>
        <script>
            function addNewUser(event) {
                // This makes a api call to add a new user
                event.preventDefault();
                var formdata = new FormData(event.target);
                $("#message").removeClass("text-capitalize");
                api('/api/addUser', "POST", formdata, 5000, function() {
                    $("#message").addClass("text-capitalize");
                    $("#name,#email").val("");
                });
            }
        </script>
    @elseif($req == 'own')
        <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fbulldata.nl%2Fwp-content%2Fuploads%2F2018%2F08%2FBullData_Logo_DEF.png&f=1&nofb=1&ipt=0910d259a5cd9473b0b702500ab064cd1bdc596d1d53e14ce312c0b046dbca39&ipo=images"
            alt="bulldata" style="width:15rem; position:absolute; top:0;">
        <form onsubmit="updateOwnAccount(event)">
            @csrf
            <div class="card w-50 mx-auto bg-red center-small-top" style="margin-top:15%;">
                <div class="card-title text-uppercase text-center">
                    <h3>eigen account</h3>
                    <div class="br-bottom"></div>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <label for="name" class="input-group-text">Naam</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ Auth::user()->name }}">
                    </div>
                    <div class="input-group">
                        <label for="email" class="input-group-text">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ Auth::user()->email }}">
                    </div>
                </div>
                <div class="card-footer">
                    <a onclick="newPass({{ Auth::user()->id }})"
                        class="btn btn-success w-100 w-md-auto mb-2 mb-md-0">Nieuw wachtwoord</a>
                    <input type="submit" value="Update" class="float-end btn btn-red">
                </div>
            </div>
        </form>


        <script>
            function updateOwnAccount(event) {
                // This makes a api call to update this person his own account
                event.preventDefault();
                var data = new FormData(event.target);
                api('/api/updateOwnAccount', "POST", data);
            }
        </script>

    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
        integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function search(type) {
            // This is for searching and filtering on data
            var url = location.protocol + '//' + location.host;
            var users = checkCheckbox($("#users").find('.form-check-input'));
            location = url + "/search/" + btoa(JSON.stringify({
                "type": type,
                "users": JSON.stringify(users),
                'href': @json($org)
            }));
        }


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
