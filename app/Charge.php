<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Charge
 * @package App
 *
 * @property integer id
 *
 * @property mixed stripe_id
 * @property mixed stripe_customer_id
 *
 * @property mixed stripe_charge_id
 * @property mixed stripe_refund_id
 * @property mixed failed_at
 * @property mixed refunded_at
 * @property mixed charged_at
 *
 * @property mixed order_id
 * @property mixed seller_id
 * @property mixed campaign_id
 * @property mixed user_id
 *
 * @property mixed is_paid
 * @property mixed is_refunded
 * @property mixed is_donation
 *
 * @property mixed status
 * @property integer attempts
 *
 * @property integer amount
 * @property integer amount_fee
 * @property integer amount_refund
 * @property integer amount_fee_refund
 *
 * @property integer currency_id
 * @property Currency currency
 *
 * stripe ids:
 * ch_xxxxxxxxxxxxxxxxx - charge
 * re_xxxxxxxxxxxxxxxxx - refund
 * fee_xxxxxxxxxxxxxxxx - fee
 * acct_xxxxxxxxxxxxxxx - account
 * card_xxxxxxxxxxxxxxx - card
 * pm_xxxxxxxxxxxxxxxxx - payment method
 * pi_xxxxxxxxxxxxxxxxx - payment intent
 * ba_xxxxxxxxxxxxxxxxx - bank id
 * tr_xxxxxxxxxxxxxxxxx - transaction/payout id
 * rt_xxxxxxxxxxxxxxxxx - refresh token
 * cus_xxxxxxxxxxxxxxxx - customer id
 * tok_xxxxxxxxxxxxxxxx - token id
 * pk_xxxxxxxxxxxxxxxxx - publishable key
 *
 */
class Charge extends Model
{
	const STATUSES = 'statuses';
	const STATUS_NEEDS_TO_BE_CHARGED = 5;
	const STATUS_CHARGE_IN_PROCESS = 7;
	const STATUS_PAID_COMPLETED = 10;
	const STATUS_NEEDS_TO_BE_REFUNDED = 15;
	const STATUS_REFUND_IN_PROCESS = 17;
	const STATUS_REFUND_COMPLETED = 20;
	const STATUS_FAILED = 30;
	const STATUS_REFUND_FAILED = 31;

	const PAID = 'paid';
	const IS_PAID = 1;
	const IS_NOT_PAID = 0;

	const DONATION = 'donation';
	const IS_DONATION = 1;
	const IS_NOT_DONATION = 0;

	const REFUNDED = 'refunded';
	const IS_REFUNDED = 1;
	const IS_NOT_REFUNDED = 0;

	public static function itemAlias($type, $code = null)
	{
		$_items = [
			self::STATUSES => [
				self::STATUS_NEEDS_TO_BE_CHARGED => 'To be charged',
				self::STATUS_CHARGE_IN_PROCESS => 'Charge is in process',
				self::STATUS_PAID_COMPLETED => 'Charged',
				self::STATUS_NEEDS_TO_BE_REFUNDED => 'To be refunded',
				self::STATUS_REFUND_IN_PROCESS => 'Refund is in process',
				self::STATUS_REFUND_COMPLETED => 'Refunded',
				self::STATUS_FAILED => 'Failed',
				self::STATUS_REFUND_FAILED => 'Refund Failed',
			],
			self::PAID => [
				self::IS_PAID => 'Paid',
				self::IS_NOT_PAID => 'Not Paid',
			],
			self::REFUNDED => [
				self::IS_REFUNDED => 'Refunded',
				self::IS_NOT_REFUNDED => 'Not Refunded',
			],
		];
		if (isset($code)) {
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		} else {
			return isset($_items[$type]) ? $_items[$type] : false;
		}
	}

	protected $with = ['seller'];

	protected $fillable = [
		'user_id', 'seller_id', 'order_id', 'status', 'is_paid', 'is_refunded', 'stripe_charge_id', 'amount', 'amount_fee', 'amount_refund',
	];

    public function order(){
        return $this->belongsTo(Order::class);
	}
	
	public function suborders(){
		return $this->hasMany(Suborder::class);
	}

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function campaign(){
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function seller(){
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public static function findChargesForCronToPaid() {
    	$charges = Charge::withoutGlobalScope('noDonations')->where(
    		[
    			'is_paid' => Charge::IS_NOT_PAID,
			]
		)->where(
			'attempts', '<', 3
		)->whereIn('status',
			[
				Charge::STATUS_FAILED,
				Charge::STATUS_NEEDS_TO_BE_CHARGED
			]
		)->get();
    	foreach ($charges as $charge) {
			$charge->status = Charge::STATUS_CHARGE_IN_PROCESS;
			$charge->save();
		}
    	return $charges;
	}

	public static function refundAllowed(self $charge) {
    	if (
    		(strtotime($charge->charged_at) + env('REFUND_TIME') * 3600 * 24) >= time() &&
			$charge->is_paid == Charge::IS_PAID &&
			$charge->is_refunded == Charge::IS_NOT_REFUNDED
		) {
    		return true;
		}
		return false;
	}

	public function successful(): bool
  {
    return $this->status == self::STATUS_PAID_COMPLETED && $this->is_paid === self::IS_PAID;
  }

	/**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('noDonations', function (Builder $builder) {
            $builder->where('is_donation',0);
        });
    }

}