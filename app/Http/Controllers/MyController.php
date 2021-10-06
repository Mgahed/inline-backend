<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyController extends Controller
{
    public function my_controller(Request $request)
    {
//        return $request;
        $sum = 0;
        foreach ($request->items as $item) {
            $sum += $item;
        }
        return $sum;
    }

    public function google_map(Request $request)
    {
        $unit = "K";
//        $earthRadius = 6371000;
        $lat1 = $request->lat1; //'31.207495422033052';
        $lon1 = $request->lon1;  //'29.962133687728013';
        $lat2 = $request->lat2; //'31.20072331347168';
        $lon2 = $request->lon2;  //'29.914172640094105';

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

//        if ($unit == "K") {
//            return ceil($miles * 1.609344) . " KM";
//        } else if ($unit == "N") {
//            return ($miles * 0.8684);
//        } else {
//            return $miles;
//        }

        return response()->json([
            "status" => true,
            "distance" => ceil($miles * 1.609344),
            "unit" => "KM"
        ],201); // ceil($miles * 1.609344) . " KM";
    }
}
