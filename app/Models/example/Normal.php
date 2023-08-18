<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Normal extends Model
{
    public function imgs()
    {
        
        return $this->hasMany(\App\MOdels\ProductImg::class,'up_id','id')->resolveJson();
    }
    use HasFactory;
}
