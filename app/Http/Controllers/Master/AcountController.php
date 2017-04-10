<?php

namespace App\Http\Controllers\Master;

use App\Filter\AccountFilter;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Account;
use Illuminate\Support\Facades\Cache;

class AcountController extends Controller
{
    //
	public function index(Request $request)
	{
		$acount = Account::all();
        if($request->exists('json')){
            return response()->json($acount->map(function ($item) {
                return ['id' => $item->id, 'text' => $item->name];
            }));
        }
		return view('master.acount', compact('acount'));

	}    
	public function create(Request $request){
		$this->validate($request, [
			'name' => 'required'
			]);
		$tambah = new Account();
		$tambah->name = $request['name'];
		$tambah->save();

		return redirect()->to('master/account');
	}

	public function saveEditAcount(Request $request, $acount_id)
	{
		$acount = Account::find($acount_id);
        $acount->name = $request->name;
        $acount->save();
        return response()->json($acount);
	}	

	public function editById($account_id)
	{
		$account = Account::find($account_id);

        return response()->json($account);	
	}

	public function delete($acount_id)
	{
		 $hapus = Account::destroy($acount_id);

        return response()->json($hapus);
	}

    /**
     * Filtering Account for configuration data
     *
     * @param AccountFilter $filter
     * @return mixed
     */
    public function filterAccount(AccountFilter $filter)
    {
        return Cache::remember('province' . $request->term, 60 * 24 * 5, function () use ($filter) {
            return Account::filter($filter)->get();
        });
	}

    /**
     * Finding Detail Account
     *
     * @param Account $account
     * @return mixed
     */
    public function find(Account $account)
    {
        return $account->name;
	}

}
