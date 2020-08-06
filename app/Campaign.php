<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

// Global filters for the model
use App\Scopes\FirstTranslationScope;

class Campaign extends Model implements Searchable
{

   use SoftDeletes, Translatable;

   public $translatedAttributes = ['name', 'description', 'slug', 'tnc'];

   public $requiredAttributes = ['name', 'description'];

   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'start_date', 'end_date', 'target', 'business_id', 'status'
   ];

   public $timestamps = true;

  public function business()
  {
     return $this->belongsTo(Business::class);
  }

  public function user()
  {
     return $this->belongsTo(User::class);
  }

  public function suborders(){
     return $this->hasMany(Suborder::class);
  }

  public function sales(){
    return $this->suborders()->has('charge')->sum('quantity');
  }

  public function getFormattedTargetAttribute(){
     return number_format($this->target,0,'',"'");
  }

  public function raised(){
     $donation_sum = $this->charges()->withoutGlobalScope('noDonations')->where('is_donation', 1)->where('is_paid', 1)->sum('amount') / 100;
     $orders = $this->suborders()->has('charge')->sum('price');
     return number_format($orders + $donation_sum,0,'',"'");
  }

   public function hasBusinessConnected(){
      return $this->business->user->isConnected();
   }

  public function charges(){
      return $this->hasMany(Charge::class);
  }

  public function getStatusLabelAttribute($value){
     return config('enum.campaign_status')[$this->status];
  }

   /**
    * Scope a query to only include active campaigns.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
   */
   public function scopeActive($query)
   {
      return $query->where('status', '1');
   }

    public function scopeActiveOrPaused($query)
    {
        return $query->whereIn('status', ['1','2']);
    }

    public function scopeHasBusinessConnected($query)
    {
        return $query->whereHas('user', static function ($q){
            $q->isConnected();
        });
    }

   /**
    * Scope a query to only include only between date range.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeDateValid($query)
  {
     $now = \Carbon\Carbon::now()->format('Y-m-d');
     return $query->whereDate('start_date','<=',$now)
                     ->whereDate('end_date','>=', $now);
  }

   /**
   * Get the products for the campaign.
   */
   public function products(){
        return $this->belongsToMany(Product::class);
   }

   public function getSearchResult(): SearchResult{

      $url = route('home', $this->slug);

      return new \Spatie\Searchable\SearchResult(
         $this,
         $this->name,
         $url
      );
   }

   public function getNameAttribute($value){
      return $value ?: $this->translations()->first()->name;
   }

   public function getDescriptionAttribute($value){
      return $value ?: $this->translations()->first()->description;
   }

}
