<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Charge;

class Suborder extends Model
{
    
    protected $with = ['product','allproduct'];
    /**
    * Get order of bought product.
    */
    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function allproduct(){
        return $this->belongsTo(Product::class,'product_id')->withTrashed();
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function charge(){
        return $this->belongsTo(Charge::class)->where('is_paid',1);
    }

    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }

    public function allcampaign(){
        return $this->belongsTo(Campaign::class,'campaign_id')->withTrashed();
    }

    public function qrcodes(){
        return $this->hasMany(QrCode::class);
    }

}
