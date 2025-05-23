<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public function provinces()
    {
        return $this->hasMany(Province::class);
    }
}
