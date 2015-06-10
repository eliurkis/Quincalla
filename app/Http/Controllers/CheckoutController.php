<?php namespace Quincalla\Http\Controllers;

use Auth;
use Request;
use Session;
use Quincalla\Product;
use Quincalla\Http\Requests;
use Quincalla\Http\Requests\StoreCartRequest;

class CheckoutController extends Controller {

    public function __construct()
    {
        $this->middleware('auth.checkout', [
            'except' => ['customer', 'postCustomer' ]
        ]);
    }

    public function index()
    {
        return redirect()->route('checkout.shipping');
    }

    public function customer()
    {
        \Session::forget('checkout');
        return view('checkout.customer');
    }

    public function postCustomer()
    {
        $accountType = \Request::get("account_type");

        if ($accountType === 'existing') {

            $validUser = Auth::attempt([
                'email' => Request::get('email'),
                'password' => Request::get('password')
            ]);

            if (! $validUser) {
                return back()->with('error', 'Invalid email address or password');
            }

            $accountId = Auth::user()->id;
        } else {
            $accountId = 0;
        }

        if ($accountType !== 'existing' && $accountId > 0) {
            $accountType = 'existing';
            $accountId = 0;
        }

        $checkout = [
            'account_type' => $accountType,
            'order_id' => null,
            'account_id' => $accountId
        ];

        session(['checkout' => $checkout]);

        return redirect()->route('checkout.shipping');
    }

    public function shipping()
    {
        $checkout = session('checkout');
        $account_type = $checkout['account_type'];

        return view('checkout.shipping', compact('checkout', 'account_type'));
    }

    public function postShipping()
    {
        $checkout = session('checkout');
        $account_type = $checkout['account_type'];

        if ($account_type !== 'existing') {
            $checkout['account_email'] = Request::get('email');
        }

        $shippingAddress = [
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'full_name' => Request::get('first_name') . ' ' . Request::get('last_name'),
            'address' => Request::get('address'),
            'address1' => Request::get('address1'),
            'country' => Request::get('country'),
            'city' => Request::get('city'),
            'phone' => Request::get('phone'),
            'zipcode' => Request::get('zipcode')
        ];

        $checkout['shipping'] = $shippingAddress;

        Session::put('checkout', $checkout);


        return redirect()->route('checkout.billing');

    }

    public function billing()
    {
        $checkout = session('checkout');

        if (! isset($checkout['shipping']) || !count($checkout['shipping'])) {
            return back()->with('error', 'Invalid shipping address');
        }

        return view('checkout.billing', compact('checkout'));
    }

    public function postBilling()
    {
        $checkout = session('checkout');

        $payment = [
            'name_on_cart' => Request::get('name_on_cart'),
            'number' => Request::get('cart_number'),
            'cart_type' => Request::get('cart_type'),
            'expiration_date' => Request::get('expiration_date'),
            'ccv_code' => Request::get('ccv_code')
        ];

        $billingAddress = [
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'full_name' => Request::get('first_name') . ' ' . Request::get('last_name'),
            'address' => Request::get('address'),
            'address1' => Request::get('address1'),
            'country' => Request::get('country'),
            'city' => Request::get('city'),
            'phone' => Request::get('phone'),
            'zipcode' => Request::get('zipcode')
        ];

        $checkout['payment'] = $payment;
        $checkout['billing'] = $billingAddress;

        Session::put('checkout', $checkout);

        return redirect()->route('checkout.confirm');
    }

    public function confirm()
    {
        $checkout = session('checkout');

        if (! isset($checkout['payment']) || !count($checkout['payment'])) {
            return back()->with('error', 'Invalid payment');
        }

        return view('checkout.confirm', compact('checkout'));
    }

}
