<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Suborder;
use App\Product;

class CouponRedeem extends Mailable
{
    use Queueable, SerializesModels;

    public $suborder;

    public $product;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Suborder $suborder, Product $product)
    {
        $this->suborder = $suborder;

        $this->product = $product;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.coupon.redeem')
            ->subject(__('LiUU - Coupon Code Voucher'));
    }
}
