<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

use App\Scopes\CurrentLanguageScope;

class Product extends Model implements Searchable
{

  use SoftDeletes, Translatable;

  public $translatedAttributes = ['title', 'description', 'slug'];

  public $requiredAttributes = ['title', 'description'];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'category_id', 'units', 'price', 'start_date', 'end_date', 'image'
  ];

  public $timestamps = true;

  public function campaign(){
    return $this->campaigns()->where('status',"1")->first();
  }

  public function business(){
    return $this->campaign()->business;
  }

  public function user(){
    return $this->belongsTo(User::class);
  }

  public function category(){
    return $this->belongsTo(ProductCategory::class);
  }

  public function getFormattedPriceAttribute()
  {
    return number_format($this->price,0,'',"'");
  }

  public function activeCampaigns(){
    return $this->belongsToMany(Campaign::class)->where('status',"1");
  }

  public function campaigns(){
    return $this->belongsToMany(Campaign::class);
  }

  public function campaignsWithTrashed(){
    return $this->belongsToMany(Campaign::class)->withTrashed();
  }

  public function getNameAttribute(){
    $lang = \App::getLocale();
    return $this->{$lang};
  }

  public function orders(){
    return $this->hasMany(Suborder::class);
  }

  public function sales(){
    return $this->orders()->has('charge')->sum('quantity');
  }

  public function getSearchResult(): SearchResult{

    $url = route('home', $this->slug);

    return new \Spatie\Searchable\SearchResult(
       $this,
       $this->title,
       $url
    );
 }

 public function hasBusinessConnected(){
   return $this->campaignsWithTrashed()->first()->user->isConnected();
 }

public function scopeHasBusinessConnected($query){
    return $query->whereHas('user', static function ($q) {
        $q->isConnected();
    });
}

 public function getTitleAttribute($value){
    return $value ?: $this->translations()->first()->title;
 }

 public function getDescriptionAttribute($value){
    return $value ?: $this->translations()->first()->description;
 }

}
