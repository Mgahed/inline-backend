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
}
