<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Division;

class DivisionController extends Controller
{
	public function index(){
		$division = Division::paginate();
		return view('master.division', compact('division'));
	}
	public function create(Request $request){
		$this->validate($request, [
			'name' => 'required'
			]);
		$tambah = new Division();
		$tambah->name = $request['name'];
		$tambah->save();
		return redirect()->to('master/divisions');
	}
	
}
