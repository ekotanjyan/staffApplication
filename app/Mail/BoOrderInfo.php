<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Charge;

class BoOrderInfo extends Mailable
{
    use Queueable, SerializesModels;

    public $charge;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Charge $charge)
    {
        $this->charge = $charge;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.order.boinfo')
            ->subject( __("LiUU order #:order",['order'=>$this->charge->order->id]) );
    }
}
