<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Position;
use App\Category;

class PositionController extends Controller
{
    //
    public function index()
    {
        $position = Position::paginate();
        $category = Category::all();
        return view('master.position', compact('position', 'category'));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $tambah = new Position();
        $tambah->name = $request['name'];
        $tambah->category_id = $request['category'];
        $tambah->save();

        return redirect()->to('position');
    }

    public function getById($position_id)
    {
        $position = Position::find($position_id);

        return response()->json($position);
    }

    public function edit(Request $request, $position_id)
    {
        $position = Position::find($position_id);
        $position->name = $request->name;
        $position->save();

        return response()->json($position);
    }

    public function delete($position_id)
    {
        $hapus = Position::destroy($position_id);

        return response()->json($hapus);
    }
}
