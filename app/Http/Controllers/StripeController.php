<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Charge;
use App\ChargeException;
use App\Http\Requests\CreditCardStore;
use App\Http\Requests\Donate;
use App\Http\Requests\StripeRefund;
use App\Mail\StripeDisconnected;
use App\Order;
use App\Product;
use App\StripeTool;
use App\Suborder;
use App\User;
use App\QrCode;
use Cart;
use Illuminate\Http\Request;
use Mail;
use PDF;
use App\Mail\OrderInfo;
use App\Mail\BoOrderInfo;
use App\Mail\PaymentFailed;
use App\Mail\DonationSeller;
use App\Mail\DonationUser;
use App\Mail\Order\OrderItem;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Stripe\OAuth;
use Stripe\Stripe;

class StripeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}

	public function connect(Request $request)
	{
		if (isset($request->code) && !empty(Auth::user()->id)) {
			$stripe_secret = env('STRIPE_SECRET');
			$value = Session::get('stripe.state');
			if ($request->state && $request->state == $value) {
				Stripe::setApiKey($stripe_secret);
				try {
					$token = OAuth::token([
						'grant_type' => 'authorization_code',
						'code' => $request->code,
						'client_secret' => $stripe_secret,
					]);
					$user = Auth::user();
					$user->stripe_id = $token->stripe_user_id;
					$user->stripe_access_token = $token->access_token;
					$user->stripe_refresh_token = $token->refresh_token;
					$user->stripe_publishable_key = $token->stripe_publishable_key;
					$user->save();
					return redirect()->route('campaign.create')->with('toast',[ 'type'=>'success', 'body'=> __('You have successfully connected Stripe! You can now create your campaign.') ]);;
				} catch (\Exception $e) {
					ChargeException::saveException(null, ChargeException::SCENARIO_CONNECT_ACCOUNT, $e->getMessage());
					Session::flash('toast',[ 'type'=>'success', 'body'=> $e->getMessage() ]);
					return redirect()->route('stripe.connect');
				}
			} else {
				Session::flash('toast',[ 'type'=>'danger', 'body'=>__('State param is missing or incorrect') ]);
			}
		} else {
			Session::flash('toast',[ 'type'=>'danger', 'body'=>__('Code param is missing or incorrect') ]);
		}
		return redirect()->route('stripe.connect');
	}

  public function disconnect()
  {
    $user = Auth::user();
    Stripe::setApiKey(env('STRIPE_SECRET'));
    $deauthorize = OAuth::deauthorize([
      'client_secret' => env('STRIPE_SECRET'),
      'client_id' => env('client_id'),
      'stripe_user_id' => $user->stripe_id,
    ]);
    if ($deauthorize->stripe_user_id) {
      $user->stripe_id = null;
      $user->stripe_access_token = null;
      $user->stripe_refresh_token = null;
      $user->stripe_publishable_key = null;
      $user->save();
      Session::flash('toast', ['type' => 'success', 'body' => __('You have successfully disconnected Stripe!')]);
      Mail::to(auth()->user())->queue(new StripeDisconnected());
    } else {
      Session::flash('toast', ['type' => 'danger', 'body' => __('Could not disconnect Stripe.')]);
    }
    return redirect()->route('stripe.index');
  }

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		$user = Auth::user();
		$client_id = getenv('client_id');
		$state = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 16)), 0, 16);
		session(['stripe.state' => $state]);
		$redirect_uri = env('redirect_uri');
		return view('admin.stripe.index',
			compact('client_id', 'state', 'user', 'redirect_uri')
		);
	}


	public function sellerCharges()
	{
		$user = Auth::user();
		$charges = Charge::with('order.suborders.allproduct')->where(['seller_id' => $user->id])->get();
		return view('admin.stripe.seller-charges', compact('charges'));
	}

    /**
     * Show Donations made page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function donations_made()
	{
		$user = Auth::user();
        $charges = Charge::withoutGlobalScope('noDonations')->where('user_id', $user->id)->where('is_donation', 1)->whereNotNull('campaign_id')->orderBy('created_at','desc')->get();
        return view('admin.stripe.donations_made', compact('charges'));

	}
    /**
     * Show Donations received page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function donations_received()
    {
        $user = Auth::user();
        $charges = Charge::withoutGlobalScope('noDonations')->where('seller_id', $user->id)->where('is_donation', 1)->orderBy('created_at','desc')->get();
        return view('admin.stripe.donations_received', compact('charges'));
    }

	public function show() {}


	public function charges()
	{
		$user = Auth::user();
		$charges = Charge::where(['user_id' => $user->id])->get();
		return view('admin.stripe.charges', compact('charges'));
	}

	public function addCreditCard(CreditCardStore $request)
	{
		$user = Auth::user();
		$stripe_key = env('STRIPE_KEY');
		if (empty($user->stripe_customer_id)) {
			StripeTool::createCustomer($user);
		}
		Stripe::setApiKey(env('STRIPE_SECRET'));
		$cc = [];
		if ($request->isMethod('POST')) {
			$cc = $request->all();
			StripeTool::createSource($user, $cc['stripeToken']);
		}
		return view('admin.stripe.add-credit-card', compact('user', 'stripe_key', 'cc'));
	}

	public function deleteCreditCard()
	{
		$user = Auth::user();
		Stripe::setApiKey(env('STRIPE_SECRET'));
		if (!empty($user->stripe_customer_id)) {
			StripeTool::deleteSource($user);
		}
		return redirect()->route('stripe.add-credit-card');
	}

	public function checkout(Request $request)
	{
		$user = Auth::user();
		$data = $request->all();
		Stripe::setApiKey(env('STRIPE_SECRET'));
		if (empty($user->stripe_customer_id)) {
			StripeTool::createCustomer($user);
		}
		$deleteCC = empty($user->stripe_card_id) && empty($data['store_cc']);
		if (empty($user->stripe_card_id) && !empty($data['stripeToken'])) {
			StripeTool::createSource($user, $data['stripeToken']);
		}
		$cart =  Cart::content();
		if (!empty($cart->count())) {
			$order = new Order();
			$order->user_id = Auth::user()->id;
			$order->total = Cart::total(2, '.', '');
			$order->name = $data['name'];
			$order->email = $data['email'];
			$sellers = [];
            foreach ($cart as $item) {
                $product = Product::where(['id' => $item->id])->get()->first();

                if (!$product->hasBusinessConnected()) { // if the business removed stripe in meanwhile
                    Cart::remove($item->rowId);
                    $toastMsg = [
                        'type' => 'warning',
                        'body' => __('Sorry, some campaigns cannot receive any payment at this time. Your cart has been updated.')
                    ];
                    return redirect()->route('cart.checkout')->with('toast', $toastMsg);
                }
                if ($item->qty > $product->units) {
                    Cart::update($item->rowId, $product->units > 0 ? $product->units : 0); // update the card with max available units
                    $toastMsg = [
                        'type' => 'warning',
                        'body' => __('Sorry, some items in your cart have already been sold. Your cart has been updated.')
                    ];
                    return redirect()->route('cart.checkout')->with('toast', $toastMsg);
                }

                $seller = $product->user;
                $sellers[$seller->id] = $seller;
                $item->seller_id = $seller->id;
            }
            $order->save();
			$seller = null;
			$groupedItems = Cart::content()->groupBy('seller_id');
			foreach ($groupedItems as $seller_id => $groupedItem) {
				$seller = $sellers[$seller_id];
				$totalGroupChargeAmount = StripeTool::double2int(StripeTool::getTotal($groupedItem));
				$charge = new Charge();
				$charge->user_id = $user->id;
				$charge->seller_id = $seller_id;
				$charge->order_id = $order->id;
//				$charge->status = Charge::STATUS_NEEDS_TO_BE_CHARGED;
				$charge->is_paid = Charge::IS_NOT_PAID;
				$charge->stripe_customer_id = $user->stripe_customer_id;
				$charge->stripe_id = $seller->stripe_id;
				$charge->amount = $totalGroupChargeAmount;
				$charge->amount_fee = StripeTool::applicationFee($totalGroupChargeAmount, $seller->stripe_fee);
				$charge->currency_id = 1; // EUR currency
//				if (!empty($data['immediate_charge'])) {
        $charge->status = Charge::STATUS_CHARGE_IN_PROCESS;
        $charge->save();
        StripeTool::createCharge($charge);
//				}
//				$charge->save();

        if (!$charge->successful()) {// if not charged abort until valid payment method is used
          $charge->delete();
          $toastMsg = [
            'type' => 'warning',
            'body' => __('We were not able to complete the payment for a part of your order. Please check your credit card details and try again. If the problem persists, contact our support. Please note that a part of the order was successful - you will shortly receive an email with the details of the products for which the payment was successfully completed')
          ];
          if (!Charge::where('order_id', $order->id)->exists()) {
            $order->delete();
            $toastMsg = [
              'type' => 'danger',
              'body' => __('We were not able to complete the payment for your order. Please check your credit card details and try again. If the problem persists, contact our support')
            ];
          }
          StripeTool::deleteSource($user);
          return redirect()->route('cart.checkout')->with('toast', $toastMsg);
        }

        foreach ($groupedItem as $item) {
					$suborder = new Suborder();
					$suborder->product_id = $item->id;
					$suborder->order_id = $order->id;
					$suborder->price = $item->total(2, '.', '');
					$suborder->quantity = $item->qty;
					$suborder->campaign_id = $item->options->campaign->id ?? null;
					$suborder->charge_id = $charge->id;
					$suborder->save();

					for($i=0;$i<$item->qty;$i++){
						$itemqr = "invoices/".Str::random()."-{$item->id}.pdf";
						$qr = new QrCode;
						$qr->suborder_id = $suborder->id;
						$qr->price = $item->price;
						$qr->path = $itemqr;
						$qr->save();

						$html = (new OrderItem($suborder,$qr,$item))->render();
						PDF::loadHTML($html)->save(storage_path("app/$itemqr"));
					}

          			Cart::remove($item->rowId);

					if($charge->successful()){
						Product::find($item->id)->decrement('units',$item->qty);
					}
				}

        if ($charge->status === Charge::STATUS_PAID_COMPLETED) {
            Mail::to(auth()->user())->queue(new OrderInfo($charge));
            Mail::to($charge->seller)->queue(new BoOrderInfo($charge));
        }
			}
//			Cart::destroy();
      if ($deleteCC) {
        StripeTool::deleteSource($user);
      }
//			foreach ($order->charges as $charge) {
//				if($charge->status == Charge::STATUS_PAID_COMPLETED){
//					Mail::to( auth()->user()->email )->queue( new OrderInfo($charge) );
//				} else {
//					Mail::to( auth()->user()->email )->queue( new PaymentFailed($charge) );
//				}
//				Mail::to( $charge->seller->email )->queue( new BoOrderInfo($charge) );
//			}

			//TODO update stock units inventory.
		} else {
			return redirect('/'); //todo add flash message
		}
		return redirect()->route('stripe.charges')->with('toast',[
			'type'=>'success',
			'body'=>__('Order Completed!')
		]);
	}

	public function view($charge_id, StripeRefund $request) {

		$user = Auth::user();
		$charge = Charge::where(['id' => $charge_id])->get()->first();
		Gate::authorize('vieworder', $charge);

		if ($charge instanceof Charge) {
			if ($request->isMethod('POST') && $charge->seller_id == $user->id) {
				$data = $request->all();
				StripeTool::createRefund($charge, $user, $data['amount']);
				return redirect()->route('stripe.view',['id'=> $charge->id]);
			}
			return view('admin.stripe.view', compact('user', 'charge'));
		}
	}


	public function mailView(Charge $charge,$bo = null){

		Gate::authorize('vieworder', $charge);

		if($bo) return new \App\Mail\BoOrderInfo($charge);
		return new \App\Mail\OrderInfo($charge);

	}

	public function chargeFail(Charge $charge){

		Gate::authorize('vieworder', $charge);
		return new \App\Mail\PaymentFailed($charge);
	}

	public function donate(Donate $request) {
        $data = $request->all();
        $campaign = Campaign::where(['id' => $data['campaign_id']])->get()->first();
        if (!in_array($campaign->status, ['1', '5'], true)){ // allow donations only if 1(active) && 5(successful)
            Session::flash('toast', ['type'=>'warning', 'body'=>__('Sorry, but this campaign is currently not accepting any contribution.')]);
            return redirect()->back();
        }

		Stripe::setApiKey(env('STRIPE_SECRET'));
		$user = Auth::user();
        if (empty($user->stripe_customer_id)) {
            StripeTool::createCustomer($user);
        }
        if (empty($user->stripe_card_id) && !empty($data['stripeToken'])) {
            StripeTool::createSource($user, $data['stripeToken']);
        }

		if ($request->isMethod('POST')) {
			$seller = Campaign::where(['id' => $data['campaign_id']])->get()->first()->user;
			if (empty($seller) || !$seller->isConnected()) {
				return redirect()->route('stripe.charges')->with('toast', [
					'type' => 'danger',
					'body'=>__('Donation Error!')
				]);
			}
			$charge = new Charge();
			$charge->user_id = $user->id;
			$charge->seller_id = $seller->id;
			$charge->order_id = null;
			$charge->status = Charge::STATUS_NEEDS_TO_BE_CHARGED;
			$charge->is_paid = Charge::IS_NOT_PAID;
			$charge->stripe_customer_id = $user->stripe_customer_id;
			$charge->stripe_id = $seller->stripe_id;
			$charge->amount = StripeTool::double2int($data['donate-amount']);
			$charge->campaign_id =  $data['campaign_id'];
			$charge->amount_fee = StripeTool::applicationFee($data['donate-amount'], $seller->stripe_fee) * 100;
			$charge->currency_id = 1; // EUR currency
			$charge->is_donation = Charge::IS_DONATION;
			$charge->save();
            StripeTool::createCharge($charge);
            $charge->save();

            if($charge->status == Charge::STATUS_PAID_COMPLETED){
                Mail::to( $charge->seller )->queue( new DonationSeller($campaign, $charge) );
                Mail::to( auth()->user() )->queue( new DonationUser($campaign, $charge) );
				return redirect()->route('stripe.donations_made')->with('toast', [
					'type'=>'success',
					'body'=>__('Donation Completed! Thank you for you support.')
				]);

            } else {
                return redirect()->route('stripe.donations_made');
			}
		}
        return redirect()->route('stripe.donations_made');
	}
}
