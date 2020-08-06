<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Suborder;
use App\Product;

class CouponUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $suborder;

    public $product;

    public $used;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Suborder $suborder, Product $product, $used)
    {
        $this->suborder = $suborder;

        $this->product = $product;

        $this->used = $used;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.coupon.updated')
            ->subject(__('LiUU - Coupon Code Voucher'));
    }
}
