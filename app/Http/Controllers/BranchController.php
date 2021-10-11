<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use App\Models\BranchService;
use App\Models\services;
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
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
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
            'lat' => $request->lat,
            'lon' => $request->lon,
            'start_time' => $request->start_time,
            'close_time' => $request->close_time,
            'service_provider_id' => $request->provider_id
        ]);
        if ($new_branch) {
            return redirect()->back()->with('success', 'Branch added successfully');
        }
        return redirect()->back()->with('fail', 'Couldn\'t add service provider please try again');
    }

    public function branch_services($id)
    {
        $branch = Branches::with('services')->find($id);
        $services = services::all();
        return view('branch.branch', compact('branch', 'services'));
    }

    public function assign_services(Request $request)
    {
        $check = BranchService::where([
            ['branches_id','=',$request->branch_id],
            ['services_id','=',$request->service_id]
        ])->get();
        if ($check->count()) {
            return redirect()->back()->with('fail', 'Couldn\'t assign service that already assigned');
        }
        $assigned = BranchService::create([
            'branches_id' => $request->branch_id,
            'services_id' => $request->service_id
        ]);
        if (!$assigned) {
            return redirect()->back()->with('fail', 'Couldn\'t assign service to this branch');
        }
        return redirect()->back()->with('success', 'Service assigned successfully');
    }
}
