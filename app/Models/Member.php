<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    //
    public function services()
{
    //return $this->hasMany(Service::class, 'member_id');
    return $this->hasMany(Service::class, 'rut', 'rut');
}
}
