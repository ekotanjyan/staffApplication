<?php
namespace App\Console\Commands;

use App\Charge;
use App\ChargeException;
use App\StripeTool;
use Illuminate\Console\Command;
use Stripe\Token;

class CronPayCharge extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */

	protected $signature = 'charges';
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Process charges';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
		$charges = Charge::findChargesForCronToPaid();
		foreach ($charges as $charge) {
			StripeTool::createCharge($charge);
			$charge->save();
		}
	}

}



