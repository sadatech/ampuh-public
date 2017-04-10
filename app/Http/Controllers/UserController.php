<?php

namespace App\Http\Controllers;

use App\Reo;
use App\Store;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class UserController extends Controller
{
    /**
     * View untuk all user
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('utilities.user');
    }

    /**
     * create user baru
     * @param Request $request
     * @return User
     */
    public function create(Request $request)
    {
        return User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'name' => $request->name,
            'role' => $request->role
        ]);
    }

    /**
     * Filter reo berdasarkan nama
     *
     * @param Request $request
     * @return mixed
     */
    public function reoFilter(Request $request) {
        $city = $request->city;
        if(!$city)exit;
        $reoByCity = $this->reoByCity($city);
        return Reo::whereHas('user', function ($query) use ($request) {
            $query->where('name', 'like', "%$request->term%");
        })->with('user')->get();
    }

    /**
     * Buat dapetin wilayah REO
     * @param $city
     * @return mixed
     */
    public function reoByCity($city)
    {
        return Store::where('city_id', $city)->pluck('reo_id');
    }
}
