<?php

namespace App\Http\Controllers;

use App\Models\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceProviderController extends Controller
{

    protected function getRules()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:service_providers',
            'address' => 'required',
            'phone_number' => 'required|numeric',
            'type' => 'required',
            'image' => 'mimes:jpeg,jpg,png|required|max:5000'
        ];
    }


    public function distance($lat1, $lon1, $lat2, $lon2)
    {
        $unit = "K";

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        return ceil($miles * 1.609344);
    }

//    protected function getMSG()
//    {
//        return [
//            'name.required' => __('courses.name is required'),
//            'email.required' => __('courses.Arabic description required'),
//            'address.required' => __('courses.English description required'),
//            'phone_number.required' => __('courses.Image Link required'),
//            'type.required' => __('courses.Course Link required'),
//        ];
//    }


    public function all()
    {
        $all_service_providers = ServiceProvider::orderBy('id', 'DESC')->get();
        return view('serviceprovider.all', compact('all_service_providers'));
    }

    public function add(Request $request)
    {
        $rules = $this->getRules();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        ////Image section
        $image = $request->file('image');
        $name_gen = hexdec(uniqid());
        $img_ext = strtolower($image->getClientOriginalExtension());
        $image_name = $name_gen . '.' . $img_ext;
        $location = 'images/providers/';//public in server
        $last_image = $location . $image_name;
        $image->move($location, $image_name);
        ////End Image section


        $new_service_provider = ServiceProvider::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'type' => $request->type,
            'image' => $last_image
        ]);
        if ($new_service_provider) {
            return redirect()->route('all.service.provider');
        }
        return redirect()->back()->with('fail', 'Couldn\'t add service provider please try again');
    }

    public function details($id)
    {
        $service_provider = ServiceProvider::with('branches')->find($id);
        return view('serviceprovider.provider', compact('service_provider'));
    }





    ///////////////////////////////////////////////////
    ///                   api                       ///
    ///////////////////////////////////////////////////
    public function all_api(Request $request)
    {
        $providers = ServiceProvider::where('type', $request->provider_type)->get();
        if (!$providers) {
            return response()->json([
                "status" => false,
                'message' => 'Maybe you selected wrong type',
            ], 401);
        }

        if (!$providers->count()) {
            return response()->json([
                "status" => false,
                'message' => 'No providers for this type'
            ], 401);
        }

        foreach ($providers as $provider) {
            $provider->image = 'https://inline.mrtechnawy.com/' . $provider->image;
        }

        return response()->json([
            "status" => true,
            'providers' => $providers

        ], 201);
    }

    public function details_api(Request $request)
    {
        $service_provider = ServiceProvider::with(array('branches' => function ($query) {
            $query->whereTime('start_time', '<=', Carbon::now()->addHour(2)->format('H:i:s'))->whereTime('close_time', '>=', Carbon::now()->format('H:i:s'));
        }))->find($request->id);
        $lat1 = $request->lat;
        $lon1 = $request->lon;
        foreach ($service_provider->branches as $branch) {
//            $now_time = Carbon::now()->addHour(2)->format("H:i:s");
//            if ($branch->start_time <= $now_time) {
//                return " start time is " . $branch->start_time . " now is " . $now_time;
////                unset($branch);
//            }
            $distance = $this->distance($lat1, $lon1, $branch->lat, $branch->lon);
            $branch->distance = $distance;
        }
        if (!$service_provider) {
            return response()->json([
                "status" => false,
                'message' => 'Service provider not found'
            ], 401);
        }
        $collection = collect($service_provider->branches);
        $sorted = $collection->sortBy('distance');
        unset($service_provider->branches);
        if (!$request->lat || !$request->lon) {
            return response()->json([
                "status" => false,
                'message' => 'Missing lat or lon'
            ], 401);
        }
        return response()->json([
            "status" => true,
            "Provider" => $service_provider,
            "branches" => $sorted->values()->all()
        ], 201);
    }
}
