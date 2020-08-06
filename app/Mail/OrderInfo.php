<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Charge;

class OrderInfo extends Mailable
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
        $lang = \App::getLocale();

        $email = $this->markdown('emails.order.info')
            ->subject( __("LiUU support. Order #:order receipt.",['order'=>$this->charge->order->id]) );

        
        $attachments = [];
        foreach($this->charge->suborders as $item){          
            foreach($item->qrcodes->pluck('path') as $path){
                $attachments[$path] = [
                    'mime' => 'application/pdf'
                ];
            }                  
        }

        foreach($attachments as $file => $type){
            $email->attach(storage_path("app/$file"),$type);  
        }
        
        return $email;

    }
}
