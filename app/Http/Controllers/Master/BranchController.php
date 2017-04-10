<?php

namespace App\Http\Controllers\Master;
use DB;
use App\Filter\BranchFilter;
use App\Arina_branch;
use App\Branch_aro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
	public function filter(Request $request, BranchFilter $branchfilter)
    {
        $alreadyHaveAro = Branch_aro::has('arina_branch')->pluck('branch_id');
        return Arina_branch::filter($branchfilter)
        			->select('arina_branches.cabang','arina_branches.id')
                    ->whereNotIn('arina_branches.id', $alreadyHaveAro)
                    ->where('arina_branches.cabang','like','%'.$request->term.'%')
                    ->get();
    }
}
