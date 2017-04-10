<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaStore extends Model
{
    protected $table = 'ba_store';

    protected $fillable = ['ba_id', 'store_id'];


    protected static function boot()
    {
        parent::boot();
        static::updated(function ($model) {
            $baStore = $model->fresh()->toJson();
            $change = $model->getDirty()->toJson();
            $ba = Ba::find($baStore['ba_id']);
            BaSummary::create(['test1' => $baStore, 'test2' => $change]);
//            BaSummary::where('store_id', $baStore['store_id'])->where('ba_id', $baStore['ba_id'])->update(['ba_id' => 0]);
//            $summaryData = BaSummary::where('store_id', $change['store_id'])->where('brand_id', $ba['brand_id'])->first();
//            $summaryData->update(['ba_id' => $ba->id])->save();
        });
    }

    public function scopeCountBa($query, $baId)
    {
        return $query->where('ba_id', $baId)->count();
    }

}
