<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use App\Models\BranchService;
use App\Models\Reservation;
use App\Models\services;
use App\Rules\CloseTime;
use App\Rules\StartTime;
use DB;
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
            'start_time' => ['required', new StartTime()],
            'close_time' => ['required', new CloseTime()]
        ];
    }

    public function add(Request $request)
    {
        $rules = $this->getRules();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        if ($request->start_time >= $request->close_time) {
            return redirect()->back()->with('fail', 'Start time should less than close time')->withInput($request->all());
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
            ['branches_id', '=', $request->branch_id],
            ['services_id', '=', $request->service_id]
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

    public function simulate($branch_id, $service_id)
    {
        $branch_service = BranchService::where('branches_id', $branch_id)->where('services_id', $service_id)->first();
        return view('simulate', compact('branch_service'));
    }

    public function simulate_increment($id)
    {
        $branch_service = BranchService::findOrFail($id);
        $branch_service->update(['current_turn' => DB::raw('current_turn + 1')]);
        return redirect()->back();
        //return redirect()->route('simulate', [$branch_service->branches_id, $branch_service->services_id]);
    }

    public function simulate_reset($id)
    {
        BranchService::findOrFail($id)->update([
            'current_turn' => 0,
            'queue' => 0
        ]);
        $reservations = Reservation::where('branch_service_id', $id)->get();
        foreach ($reservations as $reservation){
            $reservation->update([
                'status' => 'finished'
            ]);
        }

        return redirect()->back();
    }


    ///////////////////////////////////////////////////
    ///                   api                       ///
    ///////////////////////////////////////////////////

    public function details_api(Request $request)
    {
        $branch = Branches::with('services')->find($request->id);
//        if (!$branch->services->count()) {
//            return response()->json([
//                "status" => false,
//                'message' => 'No services for this branch, we will add later',
//            ], 401);
//        }
        return response()->json([
            "status" => true,
            "services" => $branch->services
        ], 201);
    }

    public function current_turn(Request $request)
    {
        $branch = BranchService::select('queue', 'current_turn')->where('branches_id', $request->branch_id)->where('services_id', $request->service_id)->first();
        return response()->json([
            "status" => true,
            "result" => $branch
        ], 201);
    }
}
