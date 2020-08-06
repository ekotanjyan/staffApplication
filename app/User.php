<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Translation\HasLocalePreference;


/**
 * @property mixed stripe_id
 * @property mixed stripe_access_token
 * @property mixed stripe_refresh_token
 * @property mixed stripe_publishable_key
 * @property mixed stripe_customer_id
 * @property mixed stripe_card_id
 */

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email', 'password', 'ip_address', 'last_login', 'language'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function businesses()
    {
       return $this->hasMany(Business::class);
    }

    public function campaigns()
    {
       return $this->hasMany(Campaign::class);
    }

    public function activeCampaign(){
        return $this->campaigns()->whereStatus("1")->first();
    }

    public function products()
    {
       return $this->hasMany(Product::class);
    }

    public function allproducts()
    {
       return $this->hasMany(Product::class)->withTrashed();
    }

    public function charges(){
        return $this->hasMany(Charge::class);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmail);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPassword($token));
    }

    /**
     * Check if user has stripe accoutn connected to business
     * @return bool
     */
    public function isConnected() {
		return
			$this->stripe_id &&
			$this->stripe_access_token &&
			$this->stripe_refresh_token &&
			$this->stripe_publishable_key;
	}

    public function scopeIsConnected($query) {
        return $query->whereNotNull(['stripe_id', 'stripe_access_token', 'stripe_refresh_token', 'stripe_publishable_key']);
    }
    /**
     * Check if user has credit card
     * @return mixed
     */
	public function hasCreditCard()
    {
        return $this->stripe_card_id != '' ? true : false;
    }

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->language;
    }

}
