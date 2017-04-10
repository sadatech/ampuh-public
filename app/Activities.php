<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    protected $fillable = ['activity', 'type', 'relations_id', 'user_id'];

}
