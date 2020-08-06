<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Campaign;
use App\Charge;

class DonationUser extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $charge;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign, Charge $charge)
    {
        $this->campaign = $campaign;
        $this->charge = $charge;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.donation.approveduser')
            ->subject(__("LiUU donation for :businessname receipt",['businessname'=>$this->campaign->business->name]));
    }
}
