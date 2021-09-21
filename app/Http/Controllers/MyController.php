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
}
