<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasOne;
use App\Nova\Actions\Campaign\CampaignActive;
use App\Nova\Actions\Campaign\CampaignSuspend;
use App\Nova\Actions\Campaign\CampaignCancel;
use App\Nova\Actions\Campaign\CampaignPause;
use App\Nova\CampaignTranslation;

class Campaign extends Resource
{

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    //public static $with = [''];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Campaign::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Name'),
            HasMany::make('Campaign Translations', 'translations'),
            HasMany::make('Products'),
            HasOne::make('Business'),
            Select::make('Status')->options([
                0 => 'Not Approved',
                1 => 'Active',
                2 => 'Paused',
                3 => 'Cancelled',
                4 => 'Finished',
                5 => 'Successful'
            ])->displayUsing(function ($val) {
                switch ( $val ) {
                    case 0:  return "Not Approved";
                    case 1:  return "Active";
                    case 2:  return "Paused";
                    case 3:  return "Cancelled";
                    case 4:  return "Finished";
                    case 5:  return "Successful";
                  }
            }),
            Number::make('Target')->min(1)->max(999999)->step(0.01),
            Number::make('Raised', function () {
                return $this->raised();
            }),
            
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new CampaignActive,
            new CampaignSuspend,
            new CampaignCancel,
            new CampaignPause
        ];
    }
}
