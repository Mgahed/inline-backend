<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{

    protected function getRules()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:branches',
            'address' => 'required',
            'phone_number' => 'required|numeric',
            'start_time' => 'required',
            'close_time' => 'required'
        ];
    }

    public function add(Request $request)
    {
        $rules = $this->getRules();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $new_branch = Branches::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'start_time' => $request->start_time,
            'close_time' => $request->close_time,
            'service_provider_id' => $request->provider_id
        ]);
        if ($new_branch) {
            return redirect()->back()->with('success', 'Branch added successfully');
        }
        return redirect()->back()->with('fail', 'Couldn\'t add service provider please try again');
    }
}