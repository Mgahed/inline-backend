<?php

namespace App\Http\Controllers;

use App\Models\services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    protected function getRules()
    {
        return [
            'name' => 'required',
            'cost' => 'required|numeric|min:0'
        ];
    }

    public function all()
    {
        $services = services::all();
        return view('service.all', compact('services'));
    }

    public function add(Request $request)
    {
        $rules = $this->getRules();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $add = services::create([
            'name' => $request->name,
            'cost' => $request->cost
        ]);
        if (!$add) {
            return redirect()->back()->with('fail','Service not added, try again');
        }
        return redirect()->back()->with('success','Service added');
    }
}
