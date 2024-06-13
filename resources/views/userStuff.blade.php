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

        canvas {
            margin-left: 20px;
            margin-top: 10px;
            border: 1px solid #000;
        }

        #x-as {
            height: 30px;
            width: 100%;
            margin-left: 25px;
            position: absolute;
            display: flex;
            gap: 75px;
        }

        .img-links img {
            width: 100%;
            height: 820px;
        }

        h3 {
            margin-top: 10px;
        }

        .br-bottom {
            background-color: white;
            border: 2px;
        }

        .card-footer {
            border
        }

        .custom-btn {
            border: 1.5px solid white;
            transition: background-color 0.3s ease;
        }

        .custom-btn:hover {
            background-color: black;
        }

        .card.center-small-top {
            margin-top: 0;
        }
    </style>
</head>

@if ($req == 'statistics')

    <body onload="load()">
    @else

        <body onload="translateDate()">
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
    integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<div class="alert fixed-top text-capitalize text-center" role="alert" id="message">
</div>
<div class="img-links">
    <img src="https://picjj.com/images/2024/05/08/Vs6O1.jpeg" alt="WhatsApp Image 2024 05 08 at 11.45.03">
</div>
@if ($req == 'addHour')
    <form onsubmit="addHour(event)" style="margin-left: 260px; border-color: #EA212D;">
        @csrf
        <div class="card mx-auto w-50 bg-red center-small-top" style="margin-top:15%; background-color: #DE2F34;">
            <div class="card-title text-uppercase text-center">
                <h3 style="color: white;">voeg uren toe</h3>
                <div class="br-bottom"></div>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <label for="startTime" class="input-group-text">Begintijd</label>
                    <input type="time" name="startTime" id="startTime" class="form-control" value="00:00">
                </div>
                <div class="input-group" style="margin-top: 5px;">
                    <label for="pauze" class="input-group-text">Pauze</label>
                    <input type="time" name="pauze" id="pauze" class="form-control" value="00:00">
                </div>
                <div class="input-group" style="margin-top: 5px;">
                    <label for="endTime" class="input-group-text">Eindtijd</label>
                    <input type="time" name="endTime" id="endTime" class="form-control" value="00:00">
                </div>
                <div class="input-group" style="margin-top: 5px;">
                    <label for="date" class="input-group-text">Dag</label>
                    <input type="date" name="date" id="date" class="form-control">
                </div>
                <div class="input-group" style="margin-top: 5px;">
                    <label for="reason" class="input-group-text">Wat heeft<br /> u gedaan</label>
                    <select id="reason" class="form-select" name="bussy" onchange="updateSelect()">
                        <option disabled selected value hidden> -- select an option -- </option>
                        @foreach ($cat as $ct)
                            <option value="{{ $ct->id }}">{{ $ct->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <textarea name="explain" id="explain" style="display:none;height:100px;margin-top:5px" class="w-100"></textarea>
            </div>
            <div class="card-footer" style="margin-top: 10px;">
                <input type="submit" value="Voeg toe" class="btn btn-red float-end" style="border: 1.5px solid white;">
            </div>
        </div>
    </form>

    <script>
        function updateSelect() {
            $("#explain").show("slow");
            if ($("#reason").val() == 1) {
                $("#explain").attr("placeholder", "Wat heeft u dan wel gedaan");
            } else if ($("#reason").val() == 3)
                $("#explain").attr("placeholder",
                    "Korte uitleg bv Ik heb facebookberichten gemaakt voor ..................");
            else if ($("#reason").val() == 2)
                $("#explain").attr("placeholder",
                    "Korte uitleg bv Ik ben met de website ...... bezig en heb dit ........... gedaan");
        }

        function addHour(event) {
            // prevent default action
            event.preventDefault();
            var data = new FormData(event.target);

            // Make api call and make the data remove a callback function so it will be removed after
            api("/api/addHour", 'POST', data, 2000, function() {
                if (!$("#message").hasClass("alert-danger")) {
                    $("#reason,#explain").val("");
                    $("#startTime,#endTime,#pauze").val("00:00");
                    $("#date").val(getCurrentDate());
                }
            });
        }
    </script>
@elseif($req == 'amountOfHours')
    @if (isset($error))

        <h1 style="position:absolute;left:50%;top:50%;transform:translate(-50%,50%)">{{ $error }}</h1>
    @else
        <div class="card">
            <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fbulldata.nl%2Fwp-content%2Fuploads%2F2018%2F08%2FBullData_Logo_DEF.png&f=1&nofb=1&ipt=0910d259a5cd9473b0b702500ab064cd1bdc596d1d53e14ce312c0b046dbca39&ipo=images"
                alt="bulldata" style="width:15rem; position:absolute; top:0;" class="d-none d-md-inline-block" />
            <div class="card-body">
                @if (str_contains($weekC, $week->week))
                    <h2 class=" text-center">Deze week {{ $weekC[0]->total_time }} aan goedgekeurde uren
                    </h2>
                @else
                    <h2 class=" text-center">Deze week 00:00:00 aan goedgekeurde uren</h2>
                @endif
                @if (str_contains($weekD, $week->week))
                    <h2 class="text-center">Deze week {{ $weekD[0]->total_time }} aan afgekeurde uren</h2>
                @else
                    <h2 class="text-center">Deze week 00:00:00 aan afgekeurde uren</h2>
                @endif
                @if (str_contains($weekI, $week->week))
                    <h2 class="text-center">Deze week {{ $weekI[0]->total_time }} aan nog te controleren uren
                    </h2>
                @else
                    <h2 class="text-center">Deze week 00:00:00 aan te controleren uren</h2>
                @endif
            </div>
            @if (isset($week))
                <div class="card-footer text-center">
                    <h3><b>Week: {{ $week->week }}</b></h3>
                </div>
            @endif
        </div>
        <div style="margin-top:3rem;"></div>
        @foreach ($hours as $hour)
            @if ($hour->state == 2)
                <div class="card bg-success mx-auto mx-md w-100 w-md-50 mx-lg-auto">
                @elseif($hour->state == 3)
                    <div class="card bg-danger mx-auto mx-md w-100 w-md-50 mx-lg-auto">
                    @else
                        <div class="card mx-auto mx-md w-100 w-md-50 mx-lg-auto"
                            style="background-color:rgba(255,255,255,0.7)">
            @endif

            <div class="card-title text-center text-uppercase pt-2 pb-0 mb-0">
                <h1>{{ date_format(date_create($hour->day), 'l d F Y') }}</h1>
                <div class="br-bottom"></div>
            </div>

            <div class="card-body">
                @if ($hour->state == 2)
                    <table class="mx-auto table w-auto border table-striped table-success">
                    @elseif($hour->state == 3)
                        <table class="mx-auto table w-auto border table-striped table-danger">
                        @else
                            <table class="mx-auto table w-auto border table-striped">
                @endif


                <tr>
                    <td class="tdborder">
                        <h4><b class="float-end">begintijd</b></h4>
                    </td>
                    <td>
                        <h4>{{ $hour->startTime }}</h4>
                    </td>
                </tr>
                <tr>
                    <td class="tdborder">
                        <h4><b class="float-end">pauze</b></h4>
                    </td>
                    <td>
                        <h4>{{ $hour->pause }}</h4>
                    </td>
                </tr>
                <tr>
                    <td class="tdborder">
                        <h4><b class="float-end">eindtijd</b></h4>
                    </td>
                    <td>
                        <h4>{{ $hour->endTime }}</h4>
                    </td>
                </tr>
                </table>
            </div>
            </div>
        @endforeach
        <div style="margin-bottom:3rem;"></div>
        @if ($links->hasPages())
            <div class="ms-2 d-flex align-items-baseline">
                <div class="pe-2"><b>Week: </b></div>
                <div>{{ $links->links('pagination::own', ['week' => $weeks]) }}</div>
            </div>
        @endif
    @endif
@elseif($req == 'statistics')
    <div class="bg-red card">
        <div class="input-group p-2">
            <label class="input-group-text" style="width:181px!important;">
                <h4>De uren die</h4>
            </label>

            <select id="selection" class="form-control" oninput="changeYear()">
                <option value="2" selected>goedgekeurd</option>
                <option value="1">niet nagekeken</option>
                <option value="3">afgekeurd</option>
                <option value="*">alle</option>
            </select>
            <label class="input-group-text" style="width:auto!important;">
                <h4>zijn</h4>
            </label>
        </div>

        <hr />
        <div class="input-group p-2">
            <label for="switchYear" class="input-group-text" style="width:auto!important;">
                <h4>Wissel van jaar</h4>
            </label>
            <input type="number" name="" id="switchYear" min="2000" oninput="changeYear()"
                class="form-control">
        </div>
    </div>
    <div>
        <canvas class="bg-red" id="canvas" width="1506" height="550"></canvas>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <script>
        // The months
        var months = [
            'januari',
            'februari',
            'maart',
            'april',
            'mei',
            'juni',
            'juli',
            'augustus',
            'semptember',
            'oktober',
            'november',
            'december',
        ]
        var year = (new Date()).getFullYear();
        var chart;

        function load(select = 2) {
            // Remove this if you don't want a timeout or change the time
            // But that can lead to to many request in sort time to server what it can take it down
            // So do it at your own risk
            $("#switchYear").prop("disabled", true);
            setTimeout(() => {
                $("#switchYear").prop("disabled", false);
                $("#switchYear").focus();
            }, 1000);
            $("#year").text(year);
            $("#switchYear").val(year);

            // Make api call
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/api/statistics/' + year + "/" + select, true);
            xhr.withCredentials = true;
            xhr.onreadystatechange = function() {
                // Wait until fully completed the request
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var work = JSON.parse(this.responseText);
                    if (!work["error"]) {
                        var works = [];
                        var free = [];

                        // Fill array with zero's because then i can place the data on the right place 
                        for (var i = 0; i < months.length; i++) {
                            works.push(0);
                            free.push(0);
                        }

                        // Loop trough all the working hours and the month is the index in the array
                        for (var i = 0; i < work["work"].length; i++) {
                            works[work["work"][i]["month"]] = work["work"][i]["total"];
                        }

                        for (var i = 0; i < work["free"].length; i++) {
                            free[work["free"][i]["month"]] = work["free"][i]["total_time"];
                        }

                        // Destroy previous chart
                        if (chart)
                            chart.destroy();

                        // Make the chart
                        chart = new Chart("canvas", {
                            type: "bar",
                            data: {
                                labels: months,
                                datasets: [{
                                    label: "Gewerkt",
                                    backgroundColor: getRandomColor(),
                                    data: works,
                                }, {
                                    label: "Verlof",
                                    backgroundColor: getRandomColor(),
                                    data: free,
                                }],

                            },
                            options: {
                                legend: {
                                    display: true,
                                    position: "top"
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            callback: function(value, index, values) {
                                                return parseSecondsToTime(value);
                                            }
                                        },
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Time (hh:mm)'
                                        }
                                    }]
                                },
                                tooltips: {
                                    callbacks: {
                                        label: function(tooltipItem, data) {
                                            // This is to make the seconds readable by converting it to hh:mm
                                            const index = tooltipItem.index;
                                            const dataset = data.datasets[tooltipItem.datasetIndex];

                                            const valueInSeconds = dataset.data[index];
                                            return `${data.labels[index]}:${parseSecondsToTime(valueInSeconds)}`;
                                        }
                                    }
                                }
                            }
                        });
                    } else {
                        if (chart)
                            chart.destroy();
                        // Get canvas
                        var canva = document.getElementById("canvas");
                        var ctx = canva.getContext("2d");

                        ctx.font="50px Arial";
                        ctx.textAlign="center";
                        ctx.fillText(work["error"],canva.width-(canva.width/2),250);
                    }
                }
            }
            xhr.send();
        }

        // This is for turning seconds in hh:mm
        function parseSecondsToTime(valueInSeconds) {
            const hours = Math.floor(valueInSeconds / 3600);
            const minutes = Math.floor((valueInSeconds % 3600) / 60);

            // Format as hh:mm
            const formattedTime =
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
            return formattedTime;
        }

        // This is to get a random color
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        // This is to change the year
        function changeYear() {
            if (!isNaN($("#switchYear").val())) {
                year = $("#switchYear").val();
                load($("#selection").val());
            }
        }
    </script>
@elseif($req == 'free')
    <div class="card mx-auto w-50 bg-red" style="margin-top: 200px; background-color: #DE2F34; left: 180px;">
        <div class=" card-title">
            <h3 class="text-uppercase text-center" style="color: white; margin-top: 5px;">verlof aanvragen
            </h3>
            <div class="br-bottom"></div>
        </div>
        <div class="card-body">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="day" onchange="oneday()">
                <label class="form-check-label" for="day" style="color: white;">Een dag</label>
            </div>
            <form onsubmit="askFree(event)" id="forWeek">
                @csrf
                <input type="hidden" name="type" value="week">

                <div class="input-group" style="margin-top: 17px;">
                    <label for="startDate" class="input-group-text">Begindatum</label>
                    <input type="date" name="startDate" id="startDate" class="form-control">
                </div>
                <div class="input-group" style="margin-top: 5px;">
                    <label for="endDate" class="input-group-text">Einddatum</label>
                    <input type="date" name="endDate" id="endDate" class="form-control">
                </div>
                <div class="input-group" style="margin-top: 5px;">
                    <label for="reason" class="input-group-text">Reden</label>
                    <textarea name="reason" id="reason" class="form-control"></textarea>
                </div>
                <input type="submit" value="Aanvragen" class="btn btn-red float-end mt-3"
                    style="border: 1.5px solid white;">
            </form>
            <form onsubmit="askFree(event)" id="oneDay" style="display:none">
                @csrf
                <input type="hidden" name="type" value="day">
                <div class="input-group">
                    <label for="startDate1" class="input-group-text" style="margin-top: 15px;">Begindatum</label>
                    <input type="date" name="startDate" id="startDate1" class="form-control"
                        style="margin-top: 15px;">
                </div>
                <div class="input-group">
                    <label for="startTime" class="input-group-text" style="margin-top: 5px;">Begintijd</label>
                    <input type="time" name="startTime" id="startTime" class="form-control" value="00:00"
                        style="margin-top: 5px;">
                </div>
                <div class="input-group">
                    <label for="endTime" class="input-group-text" style="margin-top: 5px;">Eindtijd</label>
                    <input type="time" name="endTime" id="endTime" class="form-control" value="00:00"
                        style="margin-top: 5px;">
                </div>
                <div class="input-group">
                    <label for="reason" class="input-group-text" style="margin-top: 5px;">Reden</label>
                    <textarea name="reason" id="reason1" class="form-control" style="margin-top: 5px;"></textarea>
                </div>
                <input type="submit" value="Aanvragen" class="btn btn-red float-end mt-3"
                    style="border: 1.5px solid white;">
            </form>
        </div>
    </div>
    <script>
        function oneday() {
            if ($("#day").is(":checked")) {
                $("#oneDay").show();
                $("#forWeek").hide();
            } else {
                $("#forWeek").show();
                $("#oneDay").hide();
            }

        }

        function askFree(event) {
            event.preventDefault();
            var data = new FormData(event.target);

            api("/api/free", "POST", data, 2000, function() {
                if (!$("#message").hasClass("alert-danger")) {
                    $("#reason,#reason1").val('');
                    $("#startDate1,#startDate,#endDate").val(getCurrentDate());
                    $("#startTime,#endTime").val("00:00");
                }
            });
        }
    </script>
@elseif($req == 'controlFree')
    @if (isset($error))
        <h1 style="position:absolute;left:50%;top:50%;transform:translate(-50%,50%)">{{ $error }}</h1>
    @else
        <div class="card">
            <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fbulldata.nl%2Fwp-content%2Fuploads%2F2018%2F08%2FBullData_Logo_DEF.png&f=1&nofb=1&ipt=0910d259a5cd9473b0b702500ab064cd1bdc596d1d53e14ce312c0b046dbca39&ipo=images"
                alt="bulldata" style="width:15rem; position:absolute; top:0;" class="d-none d-md-inline-block" />
            <div class="card-body">
                <div class="card-body">
                    @if ($afree[0]->total_time)
                        <h2 class=" text-center">Deze week {{ $afree[0]->total_time }} aan goedgekeurde uren
                        </h2>
                    @else
                        <h2 class=" text-center">Deze week 00:00:00 aan goedgekeurde uren
                        </h2>
                    @endif
                    @if ($dfree[0]->total_time)
                        <h2 class=" text-center">Deze week {{ $dfree[0]->total_time }} aan afgekeurde uren
                        </h2>
                    @else
                        <h2 class=" text-center">Deze week 00:00:00 aan afgekeurde uren
                        </h2>
                    @endif
                    @if ($nfree[0]->total_time)
                        <h2 class=" text-center">Deze week {{ $nfree[0]->total_time }} aan niet nagekeken uren
                        </h2>
                    @else
                        <h2 class=" text-center">Deze week 00:00:00 aan niet nagekeken uren
                        </h2>
                    @endif
                </div>
            </div>
            @if (isset($week))
                <div class="card-footer text-center">
                    <h3><b>Week: {{ $week->week }}</b></h3>
                </div>
            @endif
        </div>
        <div style="margin-top:3rem;"></div>
        @if ($free->count() > 0)
            @foreach ($free as $fre)
                @if ($fre->state == 1)
                    <div class="card bg-red w-100 w-md-50 mx-lg-auto" id="{{ $fre->id }}">
                        <div class="card-title">
                            <h1 class="text-center text-uppercase">annuleer verlof</h1>
                            <div class="br-bottom"></div>
                        </div>
                        <div class="card-body">
                            @if ($fre->startTime == null)
                                <div class="input-group">
                                    <label for="startDate" class="input-group-text">Begindatum</label>
                                    <div class="form-control" id="startDate">
                                        {{ date_format(date_create($fre->startDay), 'l d F Y') }}</div>
                                </div>
                                <div class="input-group">
                                    <label for="endDate" class="input-group-text">Einddatum</label>
                                    <div class="form-control" id="endDate">
                                        {{ date_format(date_create($fre->endDay), 'l d F Y') }}</div>
                                </div>
                                <div class="input-group">
                                    <label for="reason" class="input-group-text">Reden</label>
                                    <div class="form-control" id="reason">{{ $fre->reason }}</div>
                                </div>
                        </div>
                        <div class="card-footer">
                            <a class="btn btn-red float-end" onclick='cancel({{ $fre->id }})'>Annuleer</a>
                        </div>
                    @else
                        <div class="input-group">
                            <label for="startDate" class="input-group-text">Datum</label>
                            <div class="form-control" id="startDate">
                                {{ date_format(date_create($fre->startDay), 'l d F Y') }}</div>
                        </div>
                        <div class="input-group">
                            <label for="startTime" class="input-group-text">Starttijd</label>
                            <div class="form-control" id="startTime">{{ $fre->endTime }}</div>
                        </div>
                        <div class="input-group">
                            <label for="endtime" class="input-group-text">Eindtijd</label>
                            <div class="form-control" id="endtime">{{ $fre->endTime }}</div>
                        </div>
                        <div class="input-group">
                            <label for="reason" class="input-group-text">Reden</label>
                            <div class="form-control" id="reason">{{ $fre->reason }}</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-red float-end" onclick='cancel({{ $fre->id }})'>Annuleer</a>
                    </div>
                @endif
            @else
                @if ($fre->state == 2)
                    <div class="card bg-success w-100 w-md-50 mx-lg-auto">
                    @elseif($fre->state == 3)
                        <div class="card bg-danger w-100 w-md-50 mx-lg-auto">
                @endif
                <div class="card-title">
                    <h1 class="text-center text-uppercase">
                        {{ date_format(date_create($fre->startDay), 'l d F Y') }}</h1>
                    <div class="br-bottom"></div>
                </div>
                <div class="card-body">
                    @if ($fre->startTime == null)
                        <div class="input-group">
                            <label for="startDate" class="input-group-text">Begindatum</label>
                            <div class="form-control" id="startDate">
                                {{ date_format(date_create($fre->startDay), 'l d F Y') }}</div>
                        </div>
                        <div class="input-group">
                            <label for="endDate" class="input-group-text">Einddatum</label>
                            <div class="form-control" id="endDate">
                                {{ date_format(date_create($fre->endDay), 'l d F Y') }}</div>
                        </div>
                        <div class="input-group">
                            <label for="reason" class="input-group-text">Reden</label>
                            <div class="form-control" id="reason">{{ $fre->reason }}</div>
                        </div>
                    @else
                        <div class="input-group">
                            <label for="startDate" class="input-group-text">Datum</label>
                            <div class="form-control" id="startDate">
                                {{ date_format(date_create($fre->startDay), 'l d F Y') }}</div>
                        </div>
                        <div class="input-group">
                            <label for="startTime" class="input-group-text">Starttijd</label>
                            <div class="form-control" id="startTime">{{ $fre->endTime }}</div>
                        </div>
                        <div class="input-group">
                            <label for="endtime" class="input-group-text">Eindtijd</label>
                            <div class="form-control" id="endtime">{{ $fre->endTime }}</div>
                        </div>
                        <div class="input-group">
                            <label for="reason" class="input-group-text">Reden</label>
                            <div class="form-control" id="reason">{{ $fre->reason }}</div>
                        </div>
                    @endif
                </div>
            @endif

            </div>
        @endforeach
        <div style="margin-bottom:3rem;"></div>

        @if ($links->hasPages())
            <div class="ms-2 d-flex align-items-baseline">
                <div class="pe-2"><b>Week: </b></div>
                <div>{{ $links->links('pagination::own', ['week' => $weeks]) }}</div>
            </div>
        @endif
        <script>
            function cancel(id) {
                if (confirm("Bent u er zeker van dat u dit wilt annuleren")) {
                    api('/api/cancel/' + id, 'GET');
                    $("#" + id).fadeOut('slow', 'linear', function() {
                        $('#' + id).remove();
                    });
                }
            }
        </script>
    @else
        <h1 class="text-center text-uppercase">er zijn geen verlof uren gevonden.</h1>
    @endif
@endif
@endif
<script>
    function getCurrentDate() {
        var now = new Date();

        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);

        var today = now.getFullYear() + "-" + (month) + "-" + (day);
        return today;
    }
</script>
</body>

</html>
