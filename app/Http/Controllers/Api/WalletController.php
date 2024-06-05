<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WalletController extends ApiController
{
   
    public function addMoney(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->json()->all(), [
            'amount' => ['required', 'numeric', 'min:3', 'max:100'],
        ]);

        // Check if validation fails and return error response if true
        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validation error in your request');
        }

        try {
            // Retrieve the authenticated user
            $user = User::where('id', Auth::user()->id)->first();

            // Add the amount to the user's wallet
            $user->old_balance =  $user->wallet;
            $user->wallet += $request->input('amount');
            $user->save();

            // Return success response
            return $this->sendSuccess(['wallet' => $user->wallet], 'Money added to wallet successfully');
            
        } catch (\Exception $e) {
            // Log  and return any exception that occurs during the process
            Log::error('Error adding money to wallet: ' . $e->getMessage());
            return $this->sendError([], 'An error occurred while adding money to the wallet', 500);
        }
    }
            

}
