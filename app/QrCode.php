<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Uuid;
use Carbon\Carbon;

class QrCode extends Model
{
    use Uuid;

    protected $with = ['suborder'];

    protected $table = "qr_codes";

    public function suborder(){
        return $this->belongsTo(Suborder::class);
    }


    /**
     * Validations
    */
    public function dateValid():bool 
    {
        return Carbon::now()
                ->between(Carbon::parse($this->suborder->allproduct->start_date), Carbon::parse($this->suborder->allproduct->end_date));
    }

    public function chargePaid():bool 
    {
        return ($this->suborder->charge->status ?? null) == Charge::STATUS_PAID_COMPLETED;
    }

    public function usable():bool
    {
        return $this->used < $this->price;
    }

    public function passValidation():bool 
    {
        return $this->dateValid() 
            && $this->chargePaid()
            && $this->usable();
    }

    public function scanned()
    {
        return $this->scanned_at;
    }

    public function amountLeft()
    {
        return round($this->price - $this->used,2);
    }

}
