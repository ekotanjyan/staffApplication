<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Campaign;

class CampaignPaused extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $message;

  /**
   * Create a new message instance.
   *
   * @param Campaign $campaign
   * @param $message
   */
    public function __construct(Campaign $campaign, $message = null)
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
        return $this->markdown('emails.campaign.paused')
                    ->with('reason',$this->message)
                    ->subject(__('Your campaign :name on LiUU has been paused.',['name'=>$this->campaign->name]));
    }
}
