<?php

namespace App\Http\Controllers;

use App\Models\askFree;
use App\Models\category;
use App\Models\urenRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class userlogin extends Controller
{
    public function userIndex()
    {
        return view('userIndex');
    }

    // Show the main page based on the user it role
    public function index()
    {
        if (!Auth::check())
            return view("login");
        if (Auth::user()->role == 1)
            return redirect()->route("userIndex");
        if (Auth::user()->role == 2)
            return redirect()->route("main");
    }

    // Validate the login
    public function login(Request $req)
    {
        $valid = $req->validate(
            [
                'email' => 'required|email',
                'password' => 'required'
            ],
            [
                'email.email' => "De email moet een email zijn",
                'email.required' => 'De email is verplicht',
                'password.required' => 'Het wachtwoord is verplicht'
            ]
        );

        // First it trys to login the person in.
        // When succeed check what your role is if 2 then admin else normal user.
        // When failed return With error
        if (Auth::attempt($valid)) {
            if (Auth::user()->role == "2")
                return redirect()->route("main");
            else
                return redirect()->route('userIndex');
        } else {
            return redirect()->back()->withErrors("Email en/of wachtwoord zijn niet goed");
        }
    }

    // This is for loging out
    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect()->route('index');
    }

    // This is what the user can
    public function userActions($req)
    {
        $hour = new urenRegister;

        // Check what the user wants to do
        switch ($req) {
            case 'amountOfHours':
                // Get the request pageindex.
                // If not set then set it to 0
                $pageIndex = request()->get('page', 1);

                // This is for getting the weeks in the database
                $week = $hour
                    ->selectRaw("WEEK(day,1) AS week")
                    ->where('userid', Auth::user()->id)
                    ->whereRaw('YEAR(day)=' . date("Y"))
                    ->groupBy("week")
                    ->orderBy('week', 'desc')
                    ->get();
                if ($week->count() > 0) {

                    // set the weekNumber on the array in combination with the page index
                    $weekNumber = $week[$pageIndex - 1]->week;

                    // Get all the aproved hours based on the week
                    $weekAppr = $hour
                        ->selectRaw("WEEK(day,1) AS week, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(endTime, startTime)) - TIME_TO_SEC(pause))) AS total_time")
                        ->where('userid', Auth::user()->id)
                        ->where('state', 2)
                        ->whereRaw('WEEK(day,1)=' . $weekNumber)
                        ->whereRaw('YEAR(day)=' . date("Y"))
                        ->orderBy('week', 'desc')
                        ->get();

                    // Get all the disaproved hours based on the week
                    $weekDis = $hour
                        ->selectRaw("WEEK(day,1) AS week, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(endTime, startTime)) - TIME_TO_SEC(pause))) AS total_time")
                        ->where('userid', Auth::user()->id)
                        ->where('state', 3)
                        ->whereRaw('WEEK(day,1)=' . $weekNumber)
                        ->whereRaw('YEAR(day)=' . date("Y"))
                        ->orderBy('week', 'desc')
                        ->get();

                    // Get all the hours that still has to be checked
                    $weekToCheck = $hour
                        ->selectRaw("WEEK(day,1) AS week, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(endTime, startTime)) - TIME_TO_SEC(pause))) AS total_time")
                        ->where('userid', Auth::user()->id)
                        ->where('state', 1)
                        ->whereRaw('WEEK(day,1)=' . $weekNumber)
                        ->whereRaw('YEAR(day)=' . date("Y"))
                        ->orderBy('week', 'desc')
                        ->get();
                    // dd($weekAppr);
                    // This so i now how much rows i have
                    $allData = $hour
                        ->where('userid', '=', Auth::user()->id)
                        ->whereRaw('YEAR(day)=' . date("Y"))
                        ->count();

                    // This is for making sure I can use the default pagination in laravel
                    $links = $hour
                        ->where('userid', '=', Auth::user()->id)
                        ->whereRaw('YEAR(day)=' . date("Y"))
                        ->paginate(($allData / ($week->count())));

                    // This is the data that will be shown on the page 
                    $worked = $hour
                        ->where('userid', '=', Auth::user()->id)
                        ->whereRaw('WEEK(day,1)=' . $weekNumber)
                        ->whereRaw('YEAR(day)=' . date("Y"))
                        ->orderbyRaw("WEEK(day,1) desc")
                        ->get();

                    return view("userStuff", [
                        'req' => $req,
                        'hours' => $worked,
                        'links' => $links,
                        "weekC" => $weekAppr,
                        "weekD" => $weekDis,
                        "weekI" => $weekToCheck,
                        "week" => $week[$pageIndex - 1],
                        'weeks' => $week
                    ]);
                } else {
                    return view("userStuff", [
                        'req' => $req,
                        'error' => "Er zijn geen uren gevonden"
                    ]);
                }
            case 'statistics':
                return view("userStuff", ['req' => $req]);
            case 'controlFree':
                $free = new askFree;

                // Get the index of the current requested page
                $pageIndex = request()->get('page', 1);

                // Get all the weeks out of the database 
                $week = $free
                    ->selectRaw("WEEK(startDay,1) AS week")
                    ->where('userid', Auth::user()->id)
                    ->whereRaw('YEAR(startDay)=' . date("Y"))
                    ->groupBy('week')
                    ->orderBy('week', 'desc')
                    ->get();
                if ($week->count() > 0) {

                    // Set the weekNumber based on the pageIndex and the week
                    $weekNumber = $week[$pageIndex - 1]->week;

                    // This so i now how much rows i have
                    $allData = $free
                        ->where('userid', '=', Auth::user()->id)
                        ->whereRaw('YEAR(startDay)=' . date("Y"))
                        ->count();

                    // Get all the data for the pagination
                    $links = $free
                        ->where('userid', '=', Auth::user()->id)
                        ->orderbyRaw("WEEK(startDay,1) desc")
                        ->whereRaw('YEAR(startDay)=' . date("Y"))
                        ->paginate(($allData / ($week->count())));

                    // All the days you free has been approved, disaproved, or still has to be checked
                    $fr = $free
                        ->where('userid', '=', Auth::user()->id)
                        ->whereRaw('YEAR(startDay)=' . date("Y"))
                        ->whereRaw('WEEK(startDay,1)=' . $weekNumber)
                        ->orderbyRaw("WEEK(startDay) desc")
                        ->get();

                    $fra = $free
                        ->selectRaw("SEC_TO_TIME(SUM(CASE 
        WHEN startDay = endDay THEN 86400 
        ELSE TIMESTAMPDIFF(SECOND, startDay, endDay) 
    END) + COALESCE(SUM(TIME_TO_SEC(TIMEDIFF(endTime, startTime))),0)) AS total_time")
                        ->where('userid', '=', Auth::user()->id)
                        ->whereRaw('YEAR(startDay)=' . date("Y"))
                        ->whereRaw('WEEK(startDay,1)=' . $weekNumber)
                        ->where("state", "2")
                        ->orderbyRaw("WEEK(startDay) desc")
                        ->get();
                    $frn = $free
                        ->selectRaw("SEC_TO_TIME(SUM(CASE 
        WHEN startDay = endDay THEN 86400 
        ELSE TIMESTAMPDIFF(SECOND, startDay, endDay) 
    END) + COALESCE(SUM(TIME_TO_SEC(TIMEDIFF(endTime, startTime))),0)) AS total_time")
                        ->where('userid', '=', Auth::user()->id)
                        ->whereRaw('YEAR(startDay)=' . date("Y"))
                        ->whereRaw('WEEK(startDay,1)=' . $weekNumber)
                        ->where("state", "1")
                        ->orderbyRaw("WEEK(startDay) desc")
                        ->get();
                    $frd = $free
                        ->selectRaw("SEC_TO_TIME(SUM(CASE 
        WHEN startDay = endDay THEN 86400 
        ELSE TIMESTAMPDIFF(SECOND, startDay, endDay) 
    END) + COALESCE(SUM(TIME_TO_SEC(TIMEDIFF(endTime, startTime))),0)) AS total_time")
                        ->where('userid', '=', Auth::user()->id)
                        ->whereRaw('YEAR(startDay)=' . date("Y"))
                        ->whereRaw('WEEK(startDay,1)=' . $weekNumber)
                        ->where("state", "3")
                        ->orderbyRaw("WEEK(startDay) desc")
                        ->get();

                    return view("userStuff", [
                        "req" => $req,
                        'free' => $fr,
                        'links' => $links,
                        "week" => $week[$pageIndex - 1],
                        'weeks' => $week,
                        "afree"=>$fra,
                        "nfree"=>$frn,
                        "dfree"=>$frd
                    ]);
                } else {
                    return view("userStuff", [
                        'req' => $req,
                        'error' => "Er zijn geen uren gevonden"
                    ]);
                }
            default:
                return view("userStuff", ["req" => $req, "cat" => category::all()]);
        }
    }


    // This is to get all the data to use in a graph
    public function statistics($year, $select)
    {
        $hour = new urenRegister;
        $free = new askFree;
        $totalWork = $hour
            ->selectRaw('SUM(TIME_TO_SEC(TIMEDIFF(endTime, startTime)) - TIME_TO_SEC(pause)) AS total, MONTH(day) AS month, YEAR(day) AS year')
            ->where('userid', Auth::user()->id);

        $totalFree = $free
            ->selectRaw(' CASE 
        WHEN startDay = endDay THEN 86400  -- 86400 seconds in a day
        ELSE TIMESTAMPDIFF(SECOND, startDay, endDay) 
    END + COALESCE(SUM(TIME_TO_SEC(TIMEDIFF(endTime, startTime))),0) AS total_time,userid, MONTH(startDay) AS month, YEAR(startDay) AS year')
            ->where('userid', Auth::user()->id);

        if ($select != '*') {
            $totalWork = $totalWork->where('state', strip_tags($select));
            $totalFree = $totalFree->where('state', strip_tags($select));
        }

        $totalWork = $totalWork->groupBy("month")
            ->having("year", strip_tags($year))
            ->get();
        $totalFree = $totalFree->groupBy("month")
            ->having("year", strip_tags($year))
            ->get();
        // Check if there is any data if not return a error
        if ($totalWork->count() > 0 || $totalFree->count() > 0)
            return ["work" => $totalWork, "free" => $totalFree];
        else
            return response()->json(['error' => 'Geen gegevens gevonden']);
    }

    // This is so that the user can add hours
    public function addHour(Request $req)
    {
        try {
            $req->validate([ // Validate the input
                'startTime' => 'required|date_format:H:i',
                'pauze' => 'required|date_format:H:i',
                'endTime' => 'required|date_format:H:i',
                'date' => 'required|date_format:Y-m-d',
                'bussy' => 'required|string',
                'explain' => 'required|string|min:15'
            ], [
                'startTime.required' => "De starttijd is verplicht",
                'startTime.date_format' => 'De starttijd is incorrect geformateerd',
                'pauze.required' => "De pauze is verplicht in te vullen",
                'pauze.date_format' => "De pauze is incorrect geformateerd",
                'endTime.required' => 'De eindtijd is verplicht',
                "endTime.date_format" => "De eindtijd is incorrect geformateerd",
                'date.required' => 'De datum is verplicht',
                'date.date_format' => 'De datum is incorrect geformateerd',
                'bussy.required' => 'De wat gedaan is verplicht',
                'bussy.string' => 'De wat gedaan moet een string zijn',
                'explain.required' => 'De uitleg is verplicht',
                'explain.string' => 'De uitleg moet een string zijn',
                'explain.min' => 'De uitleg moet minimaal 15 karakters lang zijn'
            ]);
            // Prevents spam by checking if there is not already hours on that day
            // But this is only if the hours are not checked
            if ($this->checkIfHourAlreadyUsed(strip_tags($req->date))) {
                return response()->json(['error' => ['U hebt al op die dag een aantal uren ingeleverd']]);
            }
            $hour = new urenRegister;
            $hour->userId = Auth::user()->id;
            $hour->startTime = strip_tags($req->startTime);
            $hour->endTime = strip_tags($req->endTime);
            $hour->pause = strip_tags($req->pauze);
            $hour->day = strip_tags($req->date);
            $hour->reason = strip_tags($req->explain);
            $hour->category = strip_tags($req->bussy);
            $hour->save();

            return response()->json(['success' => 'De uren zijn er ingezet en worden binnenkort goedgekeurt']);
        } catch (\Illuminate\Validation\ValidationException $ex) {
            // When the validation failed return a error
            return response()->json(['error' => $ex->validator->errors()->all()]);
        }
    }

    // This is to check if the hours are not already Surrendered
    public function checkIfHourAlreadyUsed($hour)
    {
        $hours =  urenRegister::where('day', $hour)->where('state', 1)->get();
        return ($hours->count() > 0 ? true : false);
    }

    // This for when the user wants to get free
    public function free(Request $req)
    {
        // Check if the user only wants a day free or more then a week
        if ($req->type == "day") {
            try {
                $req->validate(
                    [ // Validate input
                        'startDate' => 'date_format:Y-m-d|required',
                        'startTime' => "date_format:H:i",
                        'endTime' => "date_format:H:i",
                        'reason' => 'required|string|max:255|min:15'
                    ],
                    [
                        'startDate.date_format' => 'Startdag is incorrect geformateerd',
                        'startDate.required' => 'De startdag is verplicht',
                        'startTime.date_format' => 'De starttijd is incorrect geformateerd',
                        'endTime.date_format' => 'De eindtijd is incorrect geformateerd',
                        'reason.required' => 'De reden is verplicht',
                        'reason.string' => 'De reden moet een string zijn',
                        'reason.max' => 'De reden mag maximaal 255 karakters lang zijn',
                        'reason.min' => 'De reden moet minimaal 15 karakters lang zijn'
                    ]
                );
                // Check if the person hasn't ask free on this day
                if ($this->checkIfUserAlreadyAsktFree($req->startDate))
                    return response()->json(['error' => ['De verlof datum is all in gebruik']]);

                $free = new askFree;
                $free->userid = Auth::user()->id;
                $free->startDay = strip_tags($req->startDate);
                $free->endDay = strip_tags($req->startDate);
                $free->startTime = strip_tags($req->startTime);
                $free->endTime = strip_tags($req->endTime);
                $free->state = 1;
                $free->reason = strip_tags($req->reason);
                $free->save();

                return response()->json(['success' => 'De aanvraag is success vol aangekomen']);
            } catch (\Illuminate\Validation\ValidationException $ex) {
                return response()->json(['error' => $ex->validator->errors()->all()]);
            }
        } else if ($req->type == "week") {
            try {
                $req->validate(
                    [ // Validate the input
                        'startDate' => 'date_format:Y-m-d|required',
                        'endDate' => "date_format:Y-m-d|required",
                        'reason' => 'required|string|max:255|min:15'
                    ],
                    [
                        'startDate.date_format' => 'Startdag is incorrect geformateerd',
                        'startDate.required' => 'De startdag is verplicht',
                        'endDate.date_format' => 'De einddatum is incorrect geformateerd',
                        'endDate.required' => 'De einddatum is verplicht',
                        'reason.required' => 'De reden is verplicht',
                        'reason.string' => 'De reden moet een string zijn',
                        'reason.max' => 'De reden mag maximaal 255 karakters lang zijn',
                        'reason.min' => 'De reden moet minimaal 15 karakters lang zijn'
                    ]
                );
                // Check if the person hasn't ask free on this day
                if ($this->checkIfUserAlreadyAsktFree($req->startDate))
                    return response()->json(['error' => ['De verlof datum is all in gebruik']]);

                $free = new askFree;
                $free->userid = Auth::user()->id;
                $free->startDay = strip_tags($req->startDate);
                $free->endDay = strip_tags($req->endDate);
                $free->state = 1;
                $free->reason = strip_tags($req->reason);
                $free->save();

                return response()->json(['success' => 'De aanvraag is success vol aangekomen']);
            } catch (\Illuminate\Validation\ValidationException $ex) {
                return response()->json(['error' => $ex->validator->errors()->all()]);
            }
        }
    }

    // Checks if the free isn't already set in
    private function checkIfUserAlreadyAsktFree($date)
    {
        $out = askFree::where("userid", Auth::user()->id)->where('startDay', '>=', $date)->where('endDay', '<=', $date)->get();
        return ($out->count() > 0 ? true : false);
    }

    // This is for chanceling the free request
    public function cancel($id)
    {
        $free = askFree::findOrFail($id);
        // Make sure that only the hours that are not already checked
        if ($free->state == 1) {
            $free->delete();

            return response()->json(['success' => 'de uren zijn geannuleerd']);
        } else {
            return response()->json(['error' => 'de uren zijn al verwerkt en daardoor niet meer intrekbaar']);
        }
    }
}
