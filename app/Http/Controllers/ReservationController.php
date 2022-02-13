<?php

namespace App\Http\Controllers;

use App\Models\BranchService;
use App\Models\Reservation;
use App\Models\services;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function reserve(Request $request)
    {
        $branch_id = $request->branches_id;
        $service_id = $request->services_id;
        $branch_service = BranchService::where('branches_id', $branch_id)->where('services_id', $service_id)->first();
        $check_if_reserved = Reservation::where('branch_service_id', $branch_service->id)->where('user_id', auth('api')->user()->id)->where('status', 'confirmed')->first();
        if ($check_if_reserved) {
            return response()->json([
                "status" => false,
                "message" => "You already made a reservation for this service",
            ], 401);
        }
        $cost = services::findOrFail($service_id)->first()->cost;
        $diff = auth('api')->user()->wallet - $cost;
        if ($diff < 0) {
            return response()->json([
                "status" => false,
                "message" => "Insufficient amount, please topup your wallet"
            ], 401);
        }

        User::find(auth('api')->user()->id)->update(['wallet' => DB::raw('wallet-' . $cost)]);

        $branch_service = BranchService::where('branches_id', $branch_id)->where('services_id', $service_id)->first();
        $turn = $branch_service->queue + 1;
        $branch_service->update([
            'queue' => $turn
        ]);
        $reservation = Reservation::create([
            'status' => 'confirmed',
            'reservation_number' => $turn,
            'user_id' => auth('api')->user()->id,
            'service_id' => $service_id,
            'branch_service_id' => $branch_service->id
        ]);
        return response()->json([
            "status" => true,
            "message" => "Reserved successfully",
            "result" => $reservation
        ], 201);
    }

    public function my_reservation()
    {
        $my_reservations = Reservation::select('reservations.status as status', 'reservations.reservation_number as my_turn', 'branches_services.queue as queue', 'branches_services.current_turn as current_turn', 'services.name as service_name', 'branches.name as branch_name', 'branches.phone_number as branch_number')
            ->where('user_id', auth('api')->user()->id)
            ->join('branches_services', 'branches_services.id', '=', 'reservations.branch_service_id')
            ->join('services', 'services.id', '=', 'branches_services.services_id')
            ->join('branches', 'branches.id', '=', 'branches_services.branches_id')
            ->get();
        return response()->json([
            "status" => true,
            "result" => $my_reservations
        ], 201);
    }
}
