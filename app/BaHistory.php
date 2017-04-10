<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaHistory extends Model
{
    protected $table = 'history_bas';

    protected $fillable = ['ba_id', 'store_id', 'status'];

    /**
     * History punya  BA nya
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ba()
    {
        return $this->belongsTo('App\Ba');
    }

    /**
     * History punya Toko
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Store');
    }
}
