<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['name', 'message', 'status', 'role', 'read', 'ba_id', 'wip_id', 'isReplacing', 'icon'];

    public function sdf(){
        return $this->belongsToMany('App\SDF', 'notification_sdfs', 'notification_id', 'sdf_id');
    }

    public function ba()
    {
        return $this->belongsTo('App\Ba');
    }

    public function wip()
    {
        return $this->belongsTo('App\WIP');
    }
}