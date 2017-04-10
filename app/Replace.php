<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Replace extends Model
{
    protected $fillable = ['ba_id', 'description', 'status', 'candidate', 'interview_date'];

    public function baReplace () {
        return $this->belongsTo('App\Ba', 'ba_id');
    }
}
