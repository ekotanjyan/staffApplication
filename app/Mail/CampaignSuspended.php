<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Campaign;

class CampaignSuspended extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign,$message)
    {
        $this->campaign = $campaign;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.campaign.suspended')
                    ->with('reason',$this->message)
                    ->subject(__('Your campaign :name on LiUU was suspended',['name'=>$this->campaign->name]));
    }
}
