<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SP extends Model
{
    protected $table = 'sps';

    protected $fillable = ['ba_id', 'store_id', 'sp_date', 'status', 'approve'];
}
