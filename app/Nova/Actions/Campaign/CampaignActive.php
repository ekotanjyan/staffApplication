<?php

namespace App\Nova\Actions\Campaign;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class CampaignActive extends Action
{
    use InteractsWithQueue, Queueable;

    public $onlyOnDetail = true;

    public $name = 'Activate';
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {

            $activeCampaign = $model->business->campaigns->where('status','1')->first();
            if($activeCampaign){
                return Action::danger("This business has already an active campaign with id: {$activeCampaign->id}");
            }

            $model->update([
                'status' => '1'
            ]);

            Mail::to($model->user)
                ->queue(new \App\Mail\CampaignApproved($model));

        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
