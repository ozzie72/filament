<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    //

    public function numbers(){
        return $this->hasMany('App\Number');
    }


    public function createdBy(){
        return $this->belongsTo('App\User','created_user');
    }
    

}
