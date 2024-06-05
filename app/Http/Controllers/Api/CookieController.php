<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CookieController extends ApiController
{
    // Subscribe to purchase first cookie 
    public function subscribe(Request $request)
    {
        try {
            // Retrieve the authenticated user
            $user = User::where('id', Auth::user()->id)->first();

            // Check if the user is already subscribed
            if ($user->subscribed) {
                return $this->sendError('User is already subscribed', 'Already subscribed', 400);
            }

            // Mark the user as subscribed
            $user->subscribed = 1;

            // Grant the user their first cookie to enable buy cookie to user
            $user->cookies = 1;
            $user->save();

            // Return success response
            return $this->sendSuccess(['cookies' => $user->cookies], 'Subscribed successfully. First cookie granted.');
        } catch (\Exception $e) {
            // Log and return any exception that occurs during the subscription process
            Log::error('Error subscribing user: ' . $e->getMessage());
            return $this->sendError('An error occurred while subscribing the user', 'Error subscribing user', 500);
        }
    }

    //Buy cookie request
    public function buyCookie(Request $request)
    {
        try {

            // Retrieve the authenticated user
            $user = User::where('id', Auth::user()->id)->first();


            // Check if the user has enough balance to buy the cookie
            if ($user->wallet < 1) {
                return $this->sendError('Insufficient balance to buy a cookie', 'Insufficient balance', 400);
            }

            // Check if the user has at least one cookie
            if ($user->cookies < 1) {
                return $this->sendError('You need to have at least one cookie to buy another cookie', 'Insufficient cookies - Subscribe to get one cookie', 400);
            }
            $user->old_balance =  $user->wallet;
            // Deduct $1 from the user's wallet
            $user->wallet -= 1;
            $user->save();

            // Return success response
            return $this->sendSuccess(['wallet' => $user->wallet], 'Cookie bought successfully');
        } catch (\Exception $e) {
            // Log and return any exception that occurs during the buying process
            Log::error('Error buying cookie: ' . $e->getMessage());
            
            return $this->sendError('An error occurred while buying the cookie', 'Error buying cookie', 500);
        }
    }
            

}
