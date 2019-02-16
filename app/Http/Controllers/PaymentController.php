<?php

# Copy the code from below to that controller file located at app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth'); // if you want user to be logged in to use this function then uncomment this code.
    }
    
    public function handleonlinepay(Request $request){  
        
        $input = $request->input();
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                
                // Creating a customer - If you want to create customer uncomment below code.
                /*  $customer = \Stripe\Customer::create(array(
                        'email' => $request->stripeEmail,
                        'source' => $request->stripeToken,
                        'card' => $request->stripeCard
                    ));

                    $stripe_id = $customer->id;
                
                // Card instance
                // $card = \Stripe\Card::create($customer->id, $request->tokenId); 
                */
            
                $unique_id = uniqid(); // just for tracking purpose incase you want to describe something.
            
                // Charge to customer
                $charge = \Stripe\Charge::create(array(
                    'description' => "Plan: ".$input['plan']." - Amount: ".$input['amount'].' - '. $unique_id,
                    'source' => $request->stripeToken,                    
                    'amount' => (int)($input['amount'] * 100), // the mount will be consider as cent so we need to multiply with 100
                    'currency' => 'USD'
                ));
                               
                // Insert into the database
                \App\PaymentLogs::create([                                         
                    'amount'=> $input['amount'],
                    'plan'=> $input['plan'],
                    'charge_id'=>$charge->id,
                    'stripe_id'=>$unique_id,                     
                    'quantity'=>1
                ]);

                return response()->json([
                    'message' => 'Charge successful, Thank you for payment!',
                    'state' => 'success'
                ]);                
            } catch (\Exception $ex) {
                return response()->json([
                    'message' => 'There were some issue with the payment. Please try again later.',
                    'state' => 'error'
                ]);
            }             
                
    }
}
