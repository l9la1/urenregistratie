<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" type="image/png" href="https://pbs.twimg.com/media/Dsb8CVYWwAAyTlW.jpg">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body {
            background-image: url('https://picjj.com/images/2024/05/08/Vs6O1.jpeg');
            background-size: 100%;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }

        .containers {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;

        }

        h3 {
            text-align: center;
        }

        .block h2 {
            display: flex;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            letter-spacing: 1px;
            line-height: 1.5;
        }

        h1 {
            color: black;
            display: flex;
            justify-content: center;
        }

        .Uren {
            display: flex;
            flex-direction: column;
            align-items: space-around;
            margin-top: 40px;
        }

        .input-group-text {
            width: 100px;
        }

        .cnt {
            box-shadow: 5px 5px 15px 1px rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 35px;
            background-color: rgba(255, 0, 0, 0.5);
            width: 20%;
        }

        hr {
            border-top: 2px solid #fff;
        }
    </style>
</head>

<body>
    <div class="nav">
        <div class="input-group">
            <label for="month" class="input-group-text">Maand: </label>
            <input type="number" min=1 max=12 class="form-control" style="opacity:0.8" value="5" id="month"
                onchange="refreshData()" />
        </div>
        <div class="input-group">
            <label for="year" class="input-group-text">Jaar: </label>
            <input type="number" min=2000 class="form-control" style="opacity:0.8" value="2000" id="year"
                onchange="refreshData()" />
        </div>
    </div>
    <div class="Uren">
        <div>
            <h1>Uren</h1>
            <h3 id="tHour"></h3>
            <div class="containers">
                <div class="cnt">
                    <h3 id="tHour1" class="text-light"></h3>
                    <hr />
                    <div class="block">
                        <canvas id="myChart" style="width:500px;"></canvas>
                    </div>
                </div>
                <div class="cnt">
                    <h3 id="tHour2" class="text-light"></h3>
                    <hr />
                    <div class="block">
                        <canvas id="myChart1" style="width:500px;"></canvas>
                    </div>
                </div>
                <div class="cnt">
                    <h3 id="tHour3" class="text-light"></h3>
                    <hr />
                    <div class="block">
                        <canvas id="myChart2" style="width:500px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <h1>Verlof</h1>
            <h3 id="tFree"></h3>
            <div class="containers">
                <div class="cnt">
                    <h3 id="tFree1" class="text-light"></h3>
                    <hr />
                    <div class="block">
                        <canvas id="myChart3" style="width:500px;"></canvas>
                    </div>
                </div>
                <div class="cnt">
                    <h3 id="tFree2" class="text-light"></h3>
                    <hr />
                    <div class="block">
                        <canvas id="myChart4" style="width:500px;"></canvas>
                    </div>
                </div>
                <div class="cnt">
                    <h3 id="tFree3" class="text-light"></h3>
                    <hr />
                    <div class="block">
                        <canvas id="myChart5" style="width:500px;"></canvas>
                    </div>
                    </div>
                </div>
            </div>
            <div>
                <h1>Wie wat gedaan heeft</h1>
                <div class="containers">
                    <div class="cnt">
                        <h3 id="tWhat1" class="text-light"></h3>
                        <hr />
                        <div class="block">
                            <canvas id="myChart6" style="width:500px;"></canvas>
                        </div>
                    </div>

                    <div class="cnt">
                        <h3 id="tWhat2" class="text-light"></h3>
                        <hr />
                        <div class="block">
                            <canvas id="myChart7" style="width:500px;"></canvas>
                        </div>
                    </div>
                    <div class="cnt">
                        <h3 id="tWhat3" class="text-light"></h3>
                        <hr />
                        <div class="block">
                            <canvas id="myChart8" style="width:500px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
            integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</body>
<script>
    // Set the values 
    $(document).ready(function() {
        $("#month").val(new Date().getMonth() + 1);
        $("#year").val(new Date().getFullYear());
        refreshData();
    });

    function refreshData() {
        // This is for prevent spam
        $("#year,#month").prop("disabled", true);
        setTimeout(() => {
            $("#year,#month").prop("disabled", false);
            $("#month").focus();
        }, 1000);
        // Make api call
        var xhr = new XMLHttpRequest();
        xhr.open("GET", '/api/getDataForPie/' + $("#month").val() + "/" + $("#year").val(), true);
        xhr.withCredentials = true;
        xhr.onreadystatechange = function() {
            // Make sure that the call is finished
            if (xhr.readyState == 4 && xhr.status == 200) {
                var date = JSON.parse(this.responseText);
                // This is to make all the charts
                makeChart("myChart", date, "nhours", "Niet nageken uren", "hour", "tHour1");
                makeChart("myChart1", date, "ahours", "Goedgekeurde uren", "hour", "tHour2");
                makeChart("myChart2", date, "dhours", "Afgekeurde uren", "hour", "tHour3");
                makeChart("myChart3", date, "nfree", "Niet nageken uren", "free", "tFree1");
                makeChart("myChart4", date, "afree", "Goedgekeurde uren", "free", "tFree2");
                makeChart("myChart5", date, "dfree", "Afgekeurde uren", "free", "tFree3");
                makeChart("myChart6", date, "social", "Sociale media content beheren", "what", "tWhat1");
                makeChart("myChart7", date, "site", "Website", "what", "tWhat2");
                makeChart("myChart8", date, "other", "Anders", "what", "tWhat3");
            }

        }
        xhr.send();
    }

    var tot = 0;
    var prev = null;
    var chart = {};

    function makeChart(id, date, name, title, type, ids) {
        var label = [];
        var datas = [];
        var barColors = [];

        // Fill the arrays
        for (var i = 0; i < date[name].length; i++) {
            // For color
            barColors.push(getRandomColor());
            // For username
            label.push(getUserOutOfArray(date[name][i]["userid"], date["users"]));
            // Check if total_time is not null other way i get NaN
            if (date[name][i]["total_time"] != null)
                datas.push(date[name][i]["total_time"]);
            else
                datas.push(0);
        }


        $("#" + ids).text("Totaal aantal uren:\t" + parseSecondsToTime(getTotalOutOfArray(datas)));
        // Check wich type it has to count
        switch (type) {
            case "hour":
                // If prev is not type reset the variables
                if (prev != type) {
                    tot = 0;
                    prev = type
                }
                // Set prev to type if null
                if (prev == null)
                    prev = type;
                // Count it
                tot += getTotalOutOfArray(datas);

                $("#tHour").text("Totaal aantal uren:\t" + parseSecondsToTime(tot));

                break;
            case "free":
                if (prev != type) {
                    tot = 0;
                    prev = type
                }
                if (prev == null)
                    prev = type
                tot += getTotalOutOfArray(datas);
                $("#tFree").text("Totaal aantal uren:\t" + parseSecondsToTime(tot));
                break;
        }
        // Destroy chart
        if (chart[id]) {
            chart[id].destroy();
        }
        // Get canvas
        const ctx = document.getElementById(id).getContext('2d');
        // Make chart
        chart[id] = new Chart(ctx, {
            type: "pie",
            data: {
                labels: label,
                datasets: [{
                    backgroundColor: barColors,
                    data: datas
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: true,
                    text: title,
                    fontSize: 20,
                    fontColor: '#fff'
                },
                legend: {
                    labels: {
                        fontSize: 20, // Set the font size for the legend labels
                        fontColor: '#fff' // Set the text color for the legend labels
                    }
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            const index = tooltipItem.index;
                            const dataset = data.datasets[tooltipItem.datasetIndex];

                            const valueInSeconds = dataset.data[index];
                            return `${data.labels[index]}:${parseSecondsToTime(valueInSeconds)}`;
                        }
                    }
                }
            }
        });
    }

    // This is to convert seconds to hh:mm
    function parseSecondsToTime(valueInSeconds) {
        const hours = Math.floor(valueInSeconds / 3600);
        const minutes = Math.floor((valueInSeconds % 3600) / 60);

        // Format as hh:mm
        const formattedTime =
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
        return formattedTime;
    }

    // This is to count all data in a array
    function getTotalOutOfArray(datas) {
        var total = 0;
        for (var i = 0; i < datas.length; i++) {
            total += parseInt(datas[i]);
        }
        return total;
    }

    // This is so that you can get the person belongs to id
    function getUserOutOfArray(userId, userNameArray) {
        for (var i = 0; i < userNameArray.length; i++) {
            if (userNameArray[i]["id"] == userId)
                return userNameArray[i]["name"];
        }
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
</script>

</html>
