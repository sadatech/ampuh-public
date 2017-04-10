<?php

namespace App\Http\Controllers\Utilities;

use App\User;
use App\Account;
use App\City;
use App\Reo;
use App\Branch_aro;
use App\SDF;
use App\Store;
use App\Region;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mockery\Matcher\Not;
use Yajra\Datatables\Facades\Datatables;

class UsersController extends Controller
{
    public function index()
    {
        return view('utilities.users');
    }

    /**
     * Nampilin data pake datatable json
     *
     * @return json datatable
     */
    public function datatable()
    {
        $id = Auth::user()->id;
        $find_user = User::find($id);
        if ($find_user->role == "aro") {
            $users = User::distinct()
                             ->select("users.*")
                             ->join('reos', 'users.id', '=', 'reos.user_id')
                             ->join('stores', 'reos.id', '=', 'stores.reo_id')
                             ->join('cities', 'stores.city_id', '=', 'cities.id')
                             ->whereIn('cities.region_id', function($query){
                                 $query->select('region_id')
                                 ->from('users')
                                 ->join('branch_aros', 'users.id', '=', 'branch_aros.user_id')
                                 ->join('arina_branches', 'branch_aros.branch_id', '=', 'arina_branches.id')
                                 ->where('users.id','=','16');
                             })->whereNotNull('cities.region_id')->get();
        }else{
            $users = User::get();
        }
        $datatable = Datatables::of($users)
                            ->addColumn('detail', function ($user) {
                                    return '<a href="/utilities/user/'.$user->id.'/edit" class="btn yellow"><i class="fa fa-edit"></i>  </a> 
                                <a href="/utilities/user/'.$user->id.'/delete" class="btn red" onclick = "if (! confirm(\'Apakah Anda Yakin Untuk Menghapus '.$user->name.'?\')) { return false; }"><i class="fa fa-remove"></i>  </a>';
                            })
                            ->addColumn('role', function ($user) {
                                if ($user->role == "arina") {
                                    $role = "<span class='btn blue-sharp'>".$user->role."</span>";
                                }else if ($user->role == "loreal") {
                                    $role = "<span class='btn green-jungle'>".$user->role."</span>";
                                }else if ($user->role == "loreal_ho") {
                                    $role = "<span class='btn green-jungle'>".$user->role."</span>";
                                }else if ($user->role == "reo") {
                                    $role = "<span class='btn grey-cascade'>".$user->role."</span>";
                                }else if ($user->role == "aro") {
                                    $role = "<span class='btn grey-silver'>".$user->role."</span>";
                                }else {
                                    $role = "<span class='btn purple-soft'>".$user->role."</span>";
                                }
                                if ($user->role == "reo") {
                                    return ''.$role.'<a data-toggle="modal" data-id="'.$user->id.'" id="'.$user->id.'" class="opentoko btn sbold btn-outline blue-ebonyclay" data-target="#edit-toko">Show toko</a>';
                                }
                                return $role;
                            })
                            ->make(true);
        return $datatable;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
  	  $account = Account::get();
      $region = Region::get();

      $id = Auth::user()->id;
      $find_user = User::find($id);
      $reos = Reo::with('user')->get();
    	return view('utilities.form.users', compact('reos', 'account', 'region','find_user'));
    }
    public function addUsers(Request $request)
    {
        $save_user = new User();
        if ($request->akses == "invitation") {
            $this->validate($request, [
                'email'    => 'required'
            ]);
        }else{
            $this->validate($request, [
            'username' => 'required',
            'email'    => 'required',
            'retype_password'    => 'required'
            ]);
            if ($request->akses == "reo") {
                 $this->validate($request, [
                    'store_id' => 'required'
                ]);
            }
        }

        $user = User::select('id','email')
                    ->where('email',$request->email)
                    ->get();
        if($user->count()>0){
            session()->flash('sukses_input','Email Sudah Terdaftar');
            $data = array('status' => false,
                          'code'    => 0,
                          'content' => 'Email Sudah terdaftar');
        }
        else{
            $img_profile = $request->file('image_profile');
            if ($img_profile) {
                $allowed = array('png','jpg','gif');
                $img_profile_ori = time() . '-' . $img_profile->getClientOriginalName();

                $ext = pathinfo($img_profile_ori, PATHINFO_EXTENSION);
                if(in_array($ext,$allowed) ) {
                    $img_profile->move('attachment/pasfoto', $img_profile_ori);
                }
                else{
                    echo '<script>alert("extension not supported")</script>';
                    return redirect()->back();
                }
            }
            else{

            }
            if ($request->akses == "invitation") {
                // print_r($request->email);
                $save_user->email = $request->email;
                $save_user->role = $request->akses;
                $save_user->remember_token = $request->token;
                $email = $request->email;
                $data = ['title' => 'invitation from Loreal Apps','email' => $request->email,'token' => $request->token];
                Mail::send('email.content', $data, function($m) use($email){
                   $m->to($email, 'rizaldichozzone');
                   $m->subject('Email Invitation for Loreal Apps');
                });
            }
            else{
                $save_user->name = $request->username;
                $save_user->email = $request->email;
                $save_user->password = bcrypt($request->retype_password);
                $save_user->role = $request->akses;
            }
            $save_user->file = ($img_profile) ? $img_profile_ori : null;
            session()->flash('sukses_input','Data Success Inserted');
            $save_user->save();
            if ($request->akses == "reo") {
                $id_store = explode(",", $request->store_id);
                $reo = new Reo();
                $reo->user_id = $save_user->id;
                $reo->position_id = 2;
                $reo->professional_id = 2;
                $reo->division_id = 1;
                $reo->agency_id = 1;
                $reo->brand_id = 1;
                $reo->save();
                foreach ($id_store as $id_id) {
                    $id = Store::find($id_id);
                    $id->reo_id = $reo->id;
                    $id->save();
                }
            }
            if ($request->akses == "aro") {
                $exp_branch = explode(',', $request->branch_id);
                foreach ($exp_branch as $branch) {
                    $aro = new Branch_aro();
                    $aro->user_id = $save_user->id;
                    $aro->branch_id = $branch;
                    $aro->save();
                }
            }
            $data = array('status' => true,
                          'code'    => 1,
                          'content' => 'Data Success Inserted');
        }
        $response =[

            'data' => $data,

        ];

        return $response;

    }
    public function show($id)
    {
        //
    }
    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $reo = Reo::where('user_id', $user->id)->first();
        if ($user->role == "reo") {
            $store = Store::with('city')->distinct()->where('reo_id', $reo->id)->get();
        }
        if ($user->role == "aro") {
            $branch = Branch_aro::with('arina_branch')->where('user_id',$user->id)->get();
        }
        return view('utilities.form.users', compact('user','store','reo','branch'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $img_profile = $request->file('image_profile');
        // print_r($img_profile);
        if ($img_profile) {
                // echo "file di ubah";
            $allowed = array('png','jpg','gif');
            $img_profile_ori = time() . '-' . $img_profile->getClientOriginalName();

            $ext = pathinfo($img_profile_ori, PATHINFO_EXTENSION);
            if(in_array($ext,$allowed) ) {
                $img_profile->move('attachment/pasfoto', $img_profile_ori);
            }
            else{
                echo '<script>alert("extension not supported")</script>';
                return redirect()->back();
            }
        }
        else{

        }

        $user->name = $request->username;
        $user->email = $request->email;
        if ($request->retype_password != '') {
            $user->password = bcrypt($request->retype_password);
        }
        $user->role = $request->akses;
        if ($img_profile) {
            $user->file = $img_profile_ori;
        }
        $user->update();
        if ($request->akses == "reo") {
            $id_store = explode(",", $request->store_id);
            $reo = Reo::where('user_id', $user->id)->first();
            if (count($reo) > 0) {
                $search_store = Store::where('reo_id', $reo->id)->get();
                foreach ($search_store as $store) {
                    $id = Store::find($store->id);
                    $id->reo_id = null;
                    $id->save();
                }
                foreach ($id_store as $id_id) {
                    $id = Store::find($id_id);
                    $id->reo_id = $reo->id;
                    $id->save();
                }
            }
            else{
                $reo = new Reo();
                $reo->user_id = $user->id;
                $reo->position_id = 2;
                $reo->professional_id = 2;
                $reo->division_id = 1;
                $reo->agency_id = 1;
                $reo->brand_id = 1;
                $reo->save();
                foreach ($id_store as $id_id) {
                    $id = Store::find($id_id);
                    $id->reo_id = $reo->id;
                    $id->save();
                }
            }
        }else if($request->akses == "aro"){
            $search_branch = Branch_aro::where('user_id',$user->id);
            $search_branch->delete();
            $exp_branch = explode(',', $request->branch_id);
            foreach ($exp_branch as $branch) {
                $aro = new Branch_aro();
                $aro->user_id = $user->id;
                $aro->branch_id = $branch;
                $aro->save();
            }
        }else if($request->akses == "arina"){

        }
        else{
            $search_user = Reo::where('user_id', $user->id)->first();
            if (count($search_user) > 0) {
                $search_store = Store::where('reo_id', $search_user->id)->get();
                foreach ($search_store as $store) {
                    $id = Store::find($store->id);
                    $id->reo_id = null;
                    $id->save();
                }
                $reo = Reo::where('user_id', '=', $user->id)->firstOrFail();
                $reo->delete();
            }
        }
        $data = array('status' => true,
                          'code'    => 1,
                          'content' => 'Data Success Updated');
        $response =[
            'data' => $data,
        ];

        return $response;
    }
    public function showtoko(Request $request)
    {
        $data_store = array();
        $user = Reo::where('user_id', $request->id_user)->first();
        if (count($user) > 0) {
            $search_store = Store::where('reo_id', $user->id)->get();
            foreach ($search_store as $store) {
                    $data_store[] = $store;
            }
        }
        else{
            $data_store[] = array('status' => false,
                          'code'    => 0,
                          'content' => 'Your Data is not found in database reo');
        }
        return $data_store;
    }
    public function addstore(Request $request)
    {
        $user = Reo::where('user_id', $request->id_user)->first();

        $id_store = explode(",", $request->store_id);

        if (count($user) > 0) {
            foreach ($id_store as $id_id) {
                $id = Store::find($id_id);
                $id->reo_id = $user->id;
                $id->save();
            }
        }

        $data = array('status' => true,
                          'code'    => 1,
                          'content' => 'Data Success Updated');
        $response =[
            'data' => $data,
        ];

        return $response;
    }
    public function deletestore(Request $request)
    {
        $id = Store::find($request->id_toko);
        $id->reo_id = null;
        $id->save();
        $data = array('status' => true,
                          'code'    => 1,
                          'content' => 'Data Success Updated');
        $response =[
            'data' => $data,
        ];

        return $response;
    }
    public function deleteusers(Request $request , $id)
    {
        $user = User::findOrFail($id);
        if ($user->role == "reo") {
            $reo = Reo::where('user_id', $id)->first();
            $search_store = Store::where('reo_id', $reo->id)->get();
            foreach ($search_store as $store) {
                $id = Store::find($store->id);
                $id->reo_id = null;
                $id->save();
            }
        }else if ($user->role == "aro") {
            $search_branch = Branch_aro::where('user_id',$id);
            $search_branch->delete();
        }
        $user->delete();
        session()->flash('deleteusers','Data Sucess Deleted!');
        return redirect()->to('utilities/users');
    }
    public function storeReoFilter(Request $request)
    {
        // return response()->json($request->cities_name);
        $city = $request->cities_name;
        if(!$city)exit;
        return Store::select('id','store_name_1')
                    ->whereIn('city_id', $city)
                    ->where('reo_id', null)
                    ->where('store_name_1', 'like', '%'.$request->term.'%')
                    ->get();
    }
    public function emailInvitation()
    {
        $data = ['title' => 'Test Email'];
        Mail::send('email.content', $data, function($m){
           $m->to('rizaldi354313@gmail.com', 'rizaldi354313');
           $m->subject('Email Invitation for Loreal Apps');
        });
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}


// if ($save_user->save()) {
        //     session()->flash('sukses_input','data berhasil di input');
        //     echo "data berhasil di input";
        // }
        // else{
        //     session()->flash('sukses_input','Email Sudah Ada');
        //     echo "Email Sudah Ada";
        // }

        // session()->flash('sukses_input','data berhasil di input');
        // return redirect()->to('utilities/users');
        // if ($request->akses == "arina") {
        //     // echo "arina";
        // }elseif ($request->akses == "loreal") {
        //     // echo "loreal";
        // }elseif ($request->akses == "reo") {
        //     // echo "reo";
        // }elseif ($request->akses == "invitation") {
        //     // echo "invitation";
        // }
        // return response()->json($request);
        // foreach ($request->store_id as $store_id) {
        // }