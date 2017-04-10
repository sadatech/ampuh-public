<?php

namespace App\Http\Controllers\Master;

use App\Area;
use App\ArinaBrand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    //
    public function index()
    {
        $area = Area::paginate();
        return view('master.area', compact('area'));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $tambah = new Area();
        $tambah->name = $request['name'];
        $tambah->save();

        return redirect()->to('master/area');
    }

    public function getById($area_id)
    {
        $area = Area::find($area_id);

        return response()->json($area);
    }

    public function editArea(Request $request, $area_id)
    {
        $area = Area::find($area_id);
        $area->name = $request->name;
        $area->save();
        return response()->json($area);
    }

    public function deleteArea($area_id)
    {
        $hapus = Area::destroy($area_id);

        return response()->json($hapus);
    }

    /**
     * Get Chosen Arina Area
     *
     * @param Request $request
     * @return mixed
     */
    public function filter(Request $request)
    {
        return ArinaBrand::where('cabang', 'like', '%'. $request->area . '%')->get();
    }
}
