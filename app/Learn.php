<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Learn extends Model
{
    //
    protected $table = 'learns';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
