<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class CampaignTranslation extends Model
{
  use HasSlug;

  //
  protected $fillable = ['name', 'description', 'slug', 'tnc'];

  /**
   * Get the options for generating the slug.
   */
  public function getSlugOptions() : SlugOptions
  {
      return SlugOptions::create()
          ->generateSlugsFrom('name')
          ->saveSlugsTo('slug');
  }


  /**
   * Get the route key for the model.
   *
   * @return string
   */
  public function getRouteKeyName()
  {
      return 'slug';
  }

  public function campaign(){
    return $this->belongsTo(Campaign::class);
  }

}
