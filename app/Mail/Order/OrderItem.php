<?php

namespace App\Mail\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Gloudemans\Shoppingcart\CartItem;
use App\Suborder;
use App\QrCode;

class OrderItem extends Mailable
{
    use Queueable, SerializesModels;

    public $suborder;

    public $qr;

    public $item;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Suborder $suborder, QrCode $qr, CartItem $item = null)
    {
        $this->suborder = $suborder;

        $this->qr = $qr;

        $this->item = $item;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.order.item');
    }
}
