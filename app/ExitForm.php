<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExitForm extends Model
{
    protected $fillable = ['ba_id', 'stores', 'join_date', 'alasan', 'filling_date', 'effective_date', 'pending', 'resign_info'];

    protected $dates = ['join_date', 'filling_date', 'effective_date'];

    public function ba()
    {
        return $this->belongsTo('App\Ba');
    }
}
