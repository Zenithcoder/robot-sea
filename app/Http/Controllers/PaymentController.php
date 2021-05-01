<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Zenithcoder\Paypal\Paypal;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function redirectToPaypal(Request $request)
    {  
        $this->processCheckout();
        return  $this->PaypalClient()->getAuthPaymentUrl()->redirectNow();
    }

    public function handlePaypalWebhook()
    {
        $paymentDetails =  $this->PaypalClient()->getPaymentData();

        dd($paymentDetails);
        $event_types = $paymentDetails['event_types'];

          switch ($event_types)
            {
                case "PAYMENT.SALE.COMPLETED":
                    $this->completedPayment();
                    break;
                case "BILLING.SUBSCRIPTION.PAYMENT.FAILED":
                    // Handle payment failed
                    break;
                    // Handle other webhooks
                case "BILLING.SUBSCRIPTION.CANCELLED":
                        // Handle subscription cancelled
                    break;
                case "BILLING.SUBSCRIPTION.SUSPENDED":
                        // Handle subscription suspended
                    break;
                        // Handle other webhooks
                default:
                    break;
            }
    }

    public function PaypalClient(){
        return new Paypal();
    }

    public function completedPayment()
    {
        //update payment status
    }

    public function processCheckout()
    {
        try{
            $user = Auth::user();
            $subscription = Subscription::create([
                'user_id' => Auth::user()->id,
                'total'=> request()->query('amount')
            ]);


            $cartItems = json_decode( request()->query('meta'),true);
            foreach($cartItems as $cartItem)
            {
                $subscription->transactions()->create([
                    'qty' => $cartItem->qty,
                    'total' => $cartItem->qty*$cartItem->price
                ]);
            }
        } catch(\Exception $e) {

            return $e->getMessage();
        }
    }
}
