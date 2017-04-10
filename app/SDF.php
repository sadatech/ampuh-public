<?php

namespace App;

use App\Filter\QueryFilters;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class SDF extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'sdfs';

    protected $fillable = ['no_sdf', 'store_id', 'request_date', 'first_date', 'created_by', 'deleted_by', 'attachment', 'token'];

    public function brand()
    {
        return $this->belongsToMany('App\Brand', 'sdf_brands', 'sdf_id')->withPivot('numberOfBa')->wherePivot('numberOfBa', '!=', 0);
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function deleted_by()
    {
        return $this->belongsTo('App\User', 'deleted_by');
    }

    public function brandJoin($isArchive = '=',  QueryFilters $filters)
    {
        $query = SDF::join('wips', 'sdfs.id', '=', 'wips.sdf_id')
            ->join('sdf_brands', 'sdfs.id', '=', 'sdf_brands.sdf_id')
            ->join('brands', 'sdf_brands.brand_id', '=', 'brands.id')
            ->join('stores', 'sdfs.store_id', '=', 'stores.id')
            ->join('cities', 'stores.city_id', '=', 'cities.id')
            ->leftJoin('users as c', 'sdfs.created_by', '=', 'c.id')
            ->leftJoin('users as d', 'sdfs.deleted_by', '=', 'd.id')
            ->select('sdfs.*', 'sdf_brands.numberOfBa', 'brands.name as brandName', 'brands.id as brandId', 'stores.isHold', 'stores.store_name_1', 'c.name as created_name', 'd.name as deleted_name', 'wips.fullfield', 'cities.city_name','wips.isHold as hold')
            ->where('sdf_brands.numberOfBa', '!=', 0)
            ->where('wips.fullfield', $isArchive, 'fullfield')
            ->orderBy('sdfs.first_date', 'asc')
            ->groupBy('wips.sdf_id', 'sdf_brands.brand_id');

        /*if(\Auth::user()->role == 'reo') {
            $query->where('stores.reo_id', Reo::where('user_id', \Auth::id())->first()->id);
        }*/
        return $filters->apply($query);
    }

    public function wip()
    {
        return $this->hasMany('App\WIP', 'sdf_id');
    }
}