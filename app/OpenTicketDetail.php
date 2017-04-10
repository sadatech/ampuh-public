<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpenTicketDetail extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['ticket_id','message','role','attachment'];
    protected $dates = ['created_at', 'update_at', 'deleted_at'];
    protected $table = 'open_ticket_details';
    public function ticket_id()
    {
        return $this->belongsTo('App\OpenTicket','ticket_id');
    }

}
