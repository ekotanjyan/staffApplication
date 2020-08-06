<?php

namespace App;
use Cart;
use Auth;
use Stripe\Token;
use Session;

class StripeTool
{
	public static $defaultFee = 0.055;

	/**
	 * @param int $amount
	 * @param null $fee percent value
	 * @return int
	 */
	public static function applicationFee(int $amount, $fee = null)
	{
		if (is_null($fee)) {
			$fee = static::$defaultFee;
		} else {
			$fee = $fee / 100;
		}
		$feeAmount = intval(round($amount * $fee, 0));
		return $feeAmount;
	}

	public static function getTotal($collection) {
		$total = 0;
		foreach ($collection as $item) {
			$total += $item->total(2, '.', '');
		}
		return $total;
	}

	/**
	 * converts the amount to the integer cents value
	 * @param $double
	 * @return float|int
	 */
	public static function double2int($double) {
		return $double * 100;
	}

	/**
	 * converts the integer cents value to the decimal/double amount
	 * @param $int
	 * @return float
	 */
	public static function int2double($int) {
		return $int / 100;
	}

	public static function createCustomer($user) {
		$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
		try {
			$customer = $stripe->customers->create([
				'email' => $user->email,
				'description' => "LIUU customer {$user->name} {$user->last_name}",
			]);
			$user->stripe_customer_id = $customer->id;
			$user->save();
		} catch (\Exception $e) {
			ChargeException::saveException(null, ChargeException::SCENARIO_CREATE_CUSTOMER, $e->getMessage());
		}
	}

	public static function createSource($user, $stripeToken) {
		$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
		try {
			$response = $stripe->customers->update($user->stripe_customer_id, [
				'source' => $stripeToken,
			]);
			foreach ($response->sources->data as $source) {
				if ($response->default_source == $source->id) {
					$user->stripe_card_id = $response->default_source;
					$user->card_brand = $source->brand;
					$user->card_last_four = $source->last4;
					break;
				}
			}
			$user->save();
		} catch (\Exception $e) {
			Session::now('toast',[ 'type'=>'danger', 'body'=> $e->getMessage() ]);
			ChargeException::saveException(null, ChargeException::SCENARIO_ADD_SOURCE, $e->getMessage());
		}
	}

	public static function deleteSource($user) {
		$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
		try {
			$result = $stripe->customers->deleteSource($user->stripe_customer_id, $user->stripe_card_id, []);
			if ($result['deleted'] === true) {
				$user->stripe_card_id = null;
				$user->card_brand = null;
				$user->card_last_four = null;
				$user->save();
			} else {
				//todo add flash message
			}
		} catch (\Exception $e) {
			Session::now('toast',[ 'type'=>'danger', 'body'=> $e->getMessage() ]);
			ChargeException::saveException(null, ChargeException::SCENARIO_DELETE_SOURCE, $e->getMessage());
		}
	}

	public static function createRefund(Charge $charge, $seller, $amount = null, $metadata = null) {
		$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
		$options = [];
		$options['charge'] = $charge->stripe_charge_id;
		$options['refund_application_fee'] = true;
		if (!empty($amount)) {
			$options['amount'] = $amount;
		}
		try {
			$charge->status = Charge::STATUS_NEEDS_TO_BE_REFUNDED;
			$refund = $stripe->refunds->create($options, ['stripe_account' => $seller->stripe_id]);

			$charge->stripe_refund_id = $refund->id;
			$charge->refunded_at = date('Y-m-d H:i:s');
			$charge->amount_refund = $refund->amount;
			$charge->is_refunded = Charge::IS_REFUNDED;
			$charge->status = Charge::STATUS_REFUND_COMPLETED;
			$charge->save();
		} catch (\Exception $e) {
			$charge->status = Charge::STATUS_REFUND_FAILED;
			$charge->failed_at = date('Y-m-d H:i:s');
			$charge->save();
			ChargeException::saveException($charge->id, ChargeException::SCENARIO_REFUND, $e->getMessage());
		}
	}

	public static function createCharge(Charge $charge) {
		try {
			if (is_null($charge->stripe_customer_id)) {
				if (!empty($charge->user)) {
					$charge->stripe_customer_id = $charge->user->stripe_customer_id;
				}
			}
			$token = Token::create(
				[
					"customer" => $charge->stripe_customer_id,
				],
				[
					"stripe_account" => $charge->stripe_id
				]
			);
			try {
				$stripeCharge = \Stripe\Charge::create(
					[
						"amount" => $charge->amount,
						"currency" => $charge->currency->name,
						"source" => $token->id,
						"application_fee" => $charge->amount_fee,
						'description' => '',
					],
					[
						"stripe_account" => $charge->stripe_id,
					]
				);
				$charge->stripe_charge_id = $stripeCharge->id;
				$charge->is_paid = Charge::IS_PAID;
				$charge->status = Charge::STATUS_PAID_COMPLETED;
				$charge->charged_at = date('Y-m-d H:i:s');
				$charge->save();
			} catch (\Exception $e) {
				$charge->status = Charge::STATUS_FAILED;
				$charge->failed_at = date('Y-m-d H:i:s');
				Session::flash('toast',[ 'type'=>'danger', 'body'=> $e->getMessage() ]);
				ChargeException::saveException($charge->id, ChargeException::SCENARIO_CREATE_CHARGE, $e->getMessage());
			}
		} catch (\Exception $e) {
			$charge->status = Charge::STATUS_FAILED;
			$charge->failed_at = date('Y-m-d H:i:s');
			Session::flash('toast',[ 'type'=>'danger', 'body'=> $e->getMessage() ]);
			ChargeException::saveException($charge->id, ChargeException::SCENARIO_CREATE_TOKEN, $e->getMessage());
		}
		$charge->attempts += 1;
	}

}
