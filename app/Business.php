<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{

   use SoftDeletes;

   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name', 'vatid', 'description', 'category_id', 'address', 'address2', 'city', 'province', 'zip', 'country', 'telephone', 'website', 'instagram', 'facebook', 'twitter', 'accept_terms'
   ];

  public $timestamps = true;

  public function user()
  {
     return $this->belongsTo(User::class);
  }

  public function campaigns()
  {
     return $this->hasMany(Campaign::class);
  }


  public function category()
  {
     return $this->belongsTo(BusinessCategory::class);
  }

   /**
   * Business delete.
   * Soft delete campaigns on related.
   * Delete relation campaign-product.
   * Soft delete products related to campaign.
   * @return void
   */
   protected static function boot() {
      parent::boot();
      static::deleting(function($business) {
         foreach ($business->campaigns()->get() as $campaign) {
            foreach ($campaign->products()->get() as $product) {
               $product->delete();
            }
            $campaign->products()->detach();
            $campaign->delete();
         }
      });
   }

}
