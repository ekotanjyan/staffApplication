<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ProductTranslation extends Model
{
  use HasSlug;

  //
  protected $fillable = ['title', 'description', 'slug'];

  /**
   * Get the options for generating the slug.
   */
  public function getSlugOptions() : SlugOptions
  {
      return SlugOptions::create()
          ->generateSlugsFrom('title')
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

  public function product(){
    return $this->belongsTo(Product::class);
  }

}
