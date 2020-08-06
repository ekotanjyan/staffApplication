<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property  $charge_id
 * @property  $scenario
 * @property  $message
 */
class ChargeException extends Model
{

	protected $fillable = [
		'charge_id', 'scenario', 'message',
	];

	const SCENARIO_CREATE_TOKEN = 'create_token';
	const SCENARIO_CREATE_CHARGE = 'create_charge';
	const SCENARIO_CREATE_CUSTOMER = 'create_customer';
	const SCENARIO_ADD_SOURCE = 'add_source';
	const SCENARIO_DELETE_SOURCE = 'delete_source';
	const SCENARIO_CONNECT_ACCOUNT = 'connect_account';
	const SCENARIO_REFUND = 'refund';
	const SCENARIO_CREATE_PAYMENT_INTENT = 'create_payment_intent';

	public function charge() {
		return $this->belongsTo(Charge::class);
	}

	public static function saveException($charge_id, $scenario, $message) {
		$charge_exception = new ChargeException();
		$charge_exception->charge_id = $charge_id;
		$charge_exception->scenario = $scenario;
		$charge_exception->message = $message;
		$charge_exception->save();
	}

}
