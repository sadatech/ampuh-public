<?php

namespace App;

use App\Traits\ConfigTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpenTicket extends Model
{
    use SoftDeletes,ConfigTrait;
    protected $fillable = ['title','user_id','status'];
    protected $dates = ['created_at', 'update_at', 'deleted_at','due_date'];
    //
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function detail()
    {
        return $this->hasMany('App\OpenTicketDetail', 'ticket_id');
    }
}
