<?php

namespace App\Http\Controllers;

use App\Models\role;
use App\Models\User;
use App\Models\askFree;
use App\Models\category;
use App\Models\urenRegister;
use App\Http\Controllers\admin;
use Illuminate\Support\Facades\Auth;

class globalfunctions extends Controller
{
    public function welcome()
    {
        return view("welcome");
    }

    // This is for searching for specific data
    public function search($data)
    {
        $data = json_decode(base64_decode($data)); // Decode the data

        // Create equolent models
        $hour = new urenRegister;
        $user = new User;
        $free = new askFree;

        // Get all the data out of the $data.
        // And check if they are set
        $type =  strip_tags($this->checkIfEmpty($data, 'type'));
        $users = json_decode($this->checkIfEmpty($data, 'users'));
        $sDate = $this->checkIfEmpty($data, 'sDate');
        $eDate = $this->checkIfEmpty($data, 'eDate');
        $req = $this->checkIfEmpty($data, 'href');
        $states = $this->stringArrayToInt(json_decode($this->checkIfEmpty($data, 'cState')));
        $cat = json_decode($this->checkIfEmpty($data,'cat'));
        // check if de dates are not equal to each other.
        // If not then add it as a filtering
        if ($eDate != $sDate)
            $hour = $hour->where('day', '>=', $sDate)->where('day', '<=', $eDate);
        // Check if users is set.
        // And if so then add it to the filtering
        if ($users)
            $hour = $hour->whereIn('userid', $users);

        if($cat)
            $hour= $hour->whereIn('category',$cat);

        // Checks what type is
        switch ($type) {
            case 'urengoedkeuren':
                if (Auth::user()->role == 2) {
                    $hour = $hour
                    ->where('state', 1)
                    ->orderBy("day","desc")
                    ->paginate(4);

                    return view('urencheck', [
                        'req' => $type,
                        'houresTocheck' => $hour,
                        'users' => (new admin)->getUsers(),
                        'days' => (new admin)->getAllDays(),
                        'cat'=> category::all(),
                        'sDate' => $sDate,
                        'eDate' => $eDate,
                        'user' => $users,
                        'org' => $req,
                        'sCat'=>$cat
                    ]);
                }
            case 'checkuren':
                if (Auth::user()->role == 2) {
                    if ($states)
                        $hour = $hour->whereIn('state', $states);
                    $hour = $hour
                    ->orderBy("day","desc")
                    ->where("state","!=",1)
                    ->paginate(4);

                    return view('urencheck', [
                        'req' => $type,
                        'houresChecked' => $hour,
                        'users' => (new admin)->getUsers(),
                        'days' => (new admin)->getAllDays(),
                        'cat'=> category::all(),
                        'sDate' => $sDate,
                        'eDate' => $eDate,
                        'user' => $users,
                        'org' => $req,
                        'sCat'=>$cat
                    ]);
                }
            case 'showAndEdit':
                if (Auth::user()->role == 2) {
                    $usr=collect();
                    if ($users)
                        $usr = $user
                            ->whereIn("id", $users)
                            ->where('id', '!=', 1)
                            ->paginate(4);

                    return view("usercontrol", [
                        'aUser' => (new admin)->getUsers(),
                        "users" => $usr,
                        "cUser" => $users,
                        "req" => $type,
                        "roles" => role::all(),
                        "org" => $req
                    ]);
                }
            case 'freeAprove':
                if (Auth::user()->role == 2) {
                    if ($sDate != $eDate)
                        $free = $free
                            ->where('startDay', ">=", $sDate)
                            ->where('endDay', "<=", $eDate);
                    if ($users)
                        $free = $free
                            ->whereIn("userid", $users)
                            ->where('userid', '!=', 1);

                    $free = $free->where('state', 1);
                    $free = $free->paginate(4);
                    return view("freecheck", [
                        'req' => $type,
                        'free' => $free,
                        'users' => (new admin)->getUsers(),
                        "cUser" => $users,
                        'sDate' => $sDate,
                        'eDate' => $eDate,
                        "org" => $req
                    ]);
                }
            case 'checkFree':
                if (Auth::user()->role == 2) {
                    $free = $free->where("state","!=",1);
                    if ($sDate != $eDate)
                        $free = $free->where('startDay', ">=", $sDate)->where('endDay', "<=", $eDate);
                    if ($users)
                        $free = $free
                            ->whereIn("userid", $users)
                            ->where('userid', '!=', 1);
                    if ($states != null)
                        $free = $free->whereIn('state', $states);

                    $free = $free->paginate(4);

                    return view("freecheck", [
                        'req' => $type,
                        'free' => $free,
                        'users' => (new admin)->getUsers(),
                        "org" => $req,
                        'sDate' => $sDate,
                        'eDate' => $eDate,
                        "cUser" => $users,
                        'state' => $states
                    ]);
                }
        }
    }

    // This is for converting a string array to a int array
    function stringArrayToInt($arr)
    {
        if ($arr != null) {
            $arrs = [];
            foreach ($arr as $ar) {
                array_push($arrs, (int)$ar);
            }
            return $arrs;
        }
        return null;
    }

    // Download normal manual
    public function download()
    {
        return response()->download(public_path("/download/ngebruiker.pdf"));
    }
    // Download admin manual
    public function downloada()
    {
        return response()->download(public_path("/download/agebruiker.pdf"));
    }
    // Check if the data is set
    function checkIfEmpty($input, $data)
    {
        return (property_exists($input, $data) ? $input->$data : null);
    }
}
