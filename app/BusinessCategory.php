<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessCategory extends Model
{

   use SoftDeletes;

   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = [
      'en','es','it','de','fr'
   ];

   public function getNameAttribute(){
      return $this->{ \App::getLocale() };
   }

}
