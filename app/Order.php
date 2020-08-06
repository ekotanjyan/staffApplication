<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use SoftDeletes;
    
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['suborders','user','charges'];

    /**
    * Get suborders of order.
    */
    public function suborders(){
        return $this->hasMany(Suborder::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function charges(){
        return $this->hasMany(Charge::class);
    }

}
