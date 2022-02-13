<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::/*where('user_role','admin')->*/ get();
        return view('home', compact('users'));
    }

    public function set_admin($id)
    {
        User::findOrFail($id)->update([
            'user_role' => 'admin'
        ]);
        return redirect()->back();
    }

    public function set_normal($id)
    {
        User::findOrFail($id)->update([
            'user_role' => 'normal'
        ]);
        return redirect()->back();
    }
}
