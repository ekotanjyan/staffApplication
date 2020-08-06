<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\EmailForQueuing;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\CouponRedeem;
use App\Mail\CouponUpdated;
use App\Suborder;
use App\Product;
use App\Charge;
use App\QrCode;
use Auth;

class QrCodeController extends Controller
{
   
    public function product(QrCode $qr){

        $this->authorize('view', $qr->suborder);

        return view('admin.qr.show',[
            'product' => $qr->suborder->product,
            'suborder' => $qr->suborder,  
            'qr' => $qr
        ]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QrCode $qr)
    {
        $this->authorize('update', $qr->suborder);

        if( $qr->passValidation() ){

            $qr->scanned_at = $qr->scanned_at ?: Carbon::now();
            $qr->scans += 1;

            if( $qr->suborder->product->category->usage == 'single'){

                $qr->used = $qr->suborder->product->price;
                $qr->save();  
    
                Mail::to($qr->suborder->order->user)
                        ->queue(new CouponRedeem($qr->suborder,$qr->suborder->product));
                
                return back()->with('toast',[
                    'type'=>'success',
                    'body'=>__('Usage of voucher recorded!')
                ]);
    
            } else if ($qr->suborder->product->category->usage == 'multiple'){

                $request->validate([
                    'usage' => ['numeric',"max:{$qr->amountLeft()}"],
                ]);

                $qr->used += $request->usage;
                $qr->save();

                Mail::to($qr->suborder->order->user)
                    ->queue(new CouponUpdated($qr->suborder,$qr->suborder->product,$request->usage));
    
                return back()->with('toast', [
                    'type' => 'success',
                    'body' => __('Coupon Updated!')
                ]);
            }

            return back()->with('toast', [
                'type' => 'danger',
                'body' => __('Problem with qr assigned product!')
            ]);

        }

        return view('admin.qr.show',compact('suborder','product'))->with('notvalid');

    }

}
