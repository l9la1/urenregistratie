<?php

namespace App\Http\Controllers;

use App\Models\role;
use App\Models\User;
use App\Models\askFree;
use App\Models\category;
use Illuminate\Support\Str;
use App\Models\urenRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class admin extends Controller
{
    // Show the main page
    public function index()
    {
        return view("admin");
    }

    // Load all the hours that are made and wich of them are controlled
    public function uren($req)
    {
        $req = strip_tags($req);
        $uren = new urenRegister;

        $houresToCheck = $uren
            ->where("state", "1")
            ->where('userid', '!=', Auth::user()->id)
            ->orderBy("day", "desc")
            ->paginate(4);

        $houresChecked = $uren
            ->where("state", "!=", "1")
            ->where('userid', '!=', Auth::user()->id)
            ->orderBy("day", 'desc')
            ->paginate(4);

        return view("urencheck", [
            "houresTocheck" => $houresToCheck,
            "houresChecked" => $houresChecked,
            "req" => $req,
            'users' => $this->getUsers(),
            "org" => url()->current(),
            "cat" => category::all()
        ]);
    }

    // Update the row to disapproved or 3
    public function disaproveHours($id)
    {
        $this->updateHours(3, $id);
        return response()->json(['success' => "uren zijn afgekeurd"]);
    }

    // Update the row to approve or 2
    public function approveHours($id)
    {
        $this->updateHours(2, $id);
        return response()->json(['success' => "uren zijn goedgekeurd"]);
    }

    // This is for updating the hours
    private function updateHours($state, $id)
    {
        $id = strip_tags($id);
        $hour = urenRegister::findOrFail($id);

        $hour->state = strip_tags($state);
        $hour->save();
    }

    // Get all the employees
    public function usercontrol($req)
    {
        $req = strip_tags($req);
        $user = new User;

        $users = $user
            ->where('id', '!=', Auth::user()->id)
            ->where('id', '!=', 1)
            ->paginate(4);

        $roles = role::all();

        return view("usercontrol", [
            'users' => $users,
            "aUser" => $this->getUsers(),
            "req" => $req,
            "roles" => $roles,
            "org" => url()->current()
        ]);
    }

    // This is for removing the user
    public function deleteUser($id)
    {
        $id = strip_tags($id);
        User::findOrFail($id)->delete();
        askFree::where('userid', $id)->delete();
        urenRegister::where('userid', $id)->delete();

        return response()->json(['success' => "Gebruiker en de bijbehorende data is successvol verwijderd"]);
    }

    // With this function the user is updated
    public function updateUser(Request $info)
    {
        try {
            $info->validate(
                [ // Make sure that the data is valid
                    'email' => 'email|required',
                    'role' => 'integer|required',
                    'name' => 'required|string',
                ],
                [
                    "email.required" => "Email is verplicht",
                    "email.email" => "Het moet een email zijn",
                    "role.integer" => "Het moet een nummer zijn",
                    "role.required" => "De rol is verplicht",
                    "name.string" => "Naam mag geen nummers bevatten",
                    "name.required" => "De naam is verplicht"
                ]
            );

            $user = User::findOrFail(strip_tags($info->id));
            $user->email = $info->email;
            $user->role = $info->role;
            $user->name = $info->name;
            $user->save();

            return response()->json(['success' => "Gebruiker is successvol aangepast"]);
        } catch (\Illuminate\Validation\ValidationException $ex) {
            // When validation failed return it. 
            // The message is by default in english
            return response()->json(["error" => $ex->validator->errors()->all()]);
        }
    }

    // This is to update the user its password
    public function updatePass($id)
    {
        if (is_int((int)$id)) {
            $pass = $this->makePass();
            $user = User::findOrFail($id);
            $user->password = $pass[1];
            $user->save();
            return response()->json(['success' => $user->name." zijn wachtwoord is nu  " . $pass[0]]);
        } else {
            return response()->json(["error" => ["De input is geen nummer"]]);
        }
    }

    // This is to make a password
    // It returns the pass normal and encrypted
    private function makePass()
    {
        $pass = str::random(12);
        return [$pass, bcrypt($pass)];
    }

    // This function adds a user
    public function addUser(Request $req)
    {
        try {
            $req->validate(
                [ // This is to make sure the data is valid
                    'email' => 'email|required|unique:users,email',
                    'role' => 'integer|required',
                    'name' => 'required|string|unique:users,name',
                ],
                [
                    "email.required" => "Email is verplicht",
                    "email.email" => "Het moet een email zijn",
                    "email.unique" => "De email moet uniek zijn",
                    "role.integer" => "Het moet een nummer zijn",
                    "role.required" => "De rol is verplicht",
                    "name.string" => "Naam mag geen nummers bevatten",
                    "name.required" => "De naam is verplicht",
                    "name.unique" => "De naam moet uniek zijn"
                ]
            );

            // Create new user and add the data
            $pass = $this->makePass();
            $user = new User;
            $user->name = $req->name;
            $user->email = $req->email;
            $user->role = $req->role;
            $user->password = $pass[1];
            $user->save();

            return response()->json(['success' => $user->name." is successvol toegevoegd met het wachtwoord " . $pass[0]]);
        } catch (\Illuminate\Validation\ValidationException $ex) {
            // This is returned when the data is not correct
            return response()->json(["error" => $ex->validator->errors()->all()]);
        }
    }

    // The function is to update this person its own account
    public function updateOwnAccount(Request $req)
    {
        try {
            $req->validate(
                [ // Validate the data
                    'name' => 'string|required',
                    'email' => 'email|required'
                ],
                [
                    "email.required" => "Email is verplicht",
                    "email.email" => "Het moet een email zijn",
                    "name.string" => "Naam mag geen nummers bevatten",
                    "name.required" => "De naam is verplicht"
                ]
            );

            $own = User::findOrFail(Auth::user()->id);
            $own->name = $req->name;
            $own->email = $req->email;
            $own->save();

            return response()->json(['success' => 'Account is successvol aangepast']);
        } catch (\Illuminate\Validation\ValidationException $ex) {
            // This is returned when the data is not correct
            return response()->json(["error" => $ex->validator->errors()->all()]);
        }
    }

    // This is to get all the user except the admin itself
    public function getUsers()
    {
        return User::where("id", "!=", Auth::user()->id)->where('id', '!=', 1)->get();
    }

    // This is for getting all the days in the database
    public function getAllDays()
    {
        $hour = new urenRegister;

        return $hour->distinct()->select('day')->get();
    }

    // With this function is to check wich page it has to show to you.
    public function freecheck($req)
    {
        $free = new askFree;

        if ($req == "freeAprove") {
            $free = $free->where('state', 1)->paginate(4);

            return view('freeCheck', [
                'req' => $req,
                'free' => $free,
                'users' => $this->getUsers(),
                "org" => url()->current()
            ]);
        } else if ($req == "checkFree") {
            $free = $free->where('state', '!=', 1)->paginate(4);

            return view('freeCheck', [
                'req' => $req,
                'free' => $free,
                'users' => $this->getUsers(),
                "org" => url()->current()
            ]);
        }
    }

    public function aproveOrDenyFree($id, $toDo)
    {
        if ($toDo == "aprove") {

            $this->updateFree($id, 2);
            return response()->json(['success' => 'De verlof uren zijn goedgekeurd']);
        } else if ($toDo == "disaprove") {

            $this->updateFree($id, 3);
            return response()->json(['success' => 'De verlof uren zijn afgekeurd']);
        }
    }

    // This updates the free
    private function updateFree($id, $state)
    {
        $free = askFree::findOrFail(strip_tags($id));

        $free->state = $state;
        $free->save();
    }

    public function getData($month, $year)
    {
        // This is getting all the data seperated needed for a circlediagram
        return response()->json([
            'nhours' => $this->getHours($month, 1, $year),
            'ahours' => $this->getHours($month, 2, $year),
            'dhours' => $this->getHours($month, 3, $year),
            'nfree' => $this->getFree($month, 1, $year),
            'afree' => $this->getFree($month, 2, $year),
            'dfree' => $this->getFree($month, 3, $year),
            'social' => $this->getWhat($month, 1, $year),
            'site' => $this->getWhat($month, 2, $year),
            'other' => $this->getWhat($month, 3, $year),
            'users' => User::select(["id", "name"])->get()
        ]);
    }

    // This is to get all the things user dit
    private function getWhat($month, $cat, $year)
    {
        $hour = new urenRegister;

        $wh = $hour
            ->selectRaw("MONTH(day) AS Month, SUM(TIME_TO_SEC(TIMEDIFF(endTime, startTime)) - TIME_TO_SEC(pause)) AS total_time,category,userid")
            ->whereRaw("MONTH(day)=" . strip_tags($month)) // Filter on month and category and year
            ->whereRaw("YEAR(day)=" . strip_tags($year))
            ->where("category", $cat)
            ->groupBy("category")
            ->groupBy("userId")
            ->orderBy('Month', 'desc')
            ->get();
        return $wh;
    }
    // This is to get all the worked hours almost the same as getWhat
    private function getHours($month, $state, $year)
    {
        $hour = new urenRegister;

        $mt = $hour
            ->selectRaw("MONTH(day) AS Month, SUM(TIME_TO_SEC(TIMEDIFF(endTime, startTime)) - TIME_TO_SEC(pause)) AS total_time,userid")
            ->whereRaw('YEAR(day)=' . strip_tags($year))
            ->whereRaw("MONTH(day)=" . strip_tags($month))
            ->groupBy("userid")
            ->groupBy("Month")
            ->orderBy('Month', 'desc')
            ->where("state", strip_tags($state))
            ->get();
        return $mt;
    }

    // See getHours 
    private function getFree($month, $state, $year)
    {
        $hour = new askFree;

        $mt = $hour
            ->selectRaw("MONTH(startDay) AS Month, SUM(CASE 
        WHEN startDay = endDay THEN 86400 
        ELSE TIMESTAMPDIFF(SECOND, startDay, endDay) 
    END) + COALESCE(SUM(TIME_TO_SEC(TIMEDIFF(endTime, startTime))),0) AS total_time,userid")
            ->whereRaw('YEAR(startDay)=' . strip_tags($year))
            ->whereRaw("MONTH(startDay)=" . strip_tags($month))
            ->groupBy("userid")
            ->groupBy("Month")
            ->orderBy('Month', 'desc')
            ->where("state", strip_tags($state))
            ->get();
        return $mt;
    }
}
