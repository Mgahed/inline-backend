<?php

namespace App\Http\Controllers;

use App\Models\ServiceProvider;
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
        ];
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
        $new_service_provider = ServiceProvider::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'type' => $request->type
        ]);
        if ($new_service_provider) {
            return redirect()->route('all.service.provider');
        }
        return redirect()->back()->with('fail','Couldn\'t add service provider please try again');
    }
}
