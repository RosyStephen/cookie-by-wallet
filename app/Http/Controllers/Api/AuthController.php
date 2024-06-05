<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
   //New User Registration request
   public function register(Request $request): JsonResponse
   {
    
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Check if validation fails and return error response if true
        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validation error in your request');
        }

        try {
            // Create a new user with the validated data
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Fire the Registered event for the newly created user
            event(new Registered($user));

            // Return success response with the created user information
            return $this->sendSuccess(['user' => $user], 'User registered successfully'); 
        } catch (\Exception $e) {
            // Log  and return any exception that occurs during the user creation process
            Log::error('Error registering user: ' . $e->getMessage());     
            return $this->sendError('An error occurred while registering the user', 500);
        }
            

   }

   //Login request
   public function login(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        // Check if validation fails and return error response if true
        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validation error in your request');
        }

        try {
            // Attempt to authenticate the user with the provided credentials
            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return $this->sendError([],'Invalid credentials', 401);
            }

            // Retrieve the authenticated user
            $user = User::where('id', Auth::user()->id)->first();

            // Create a new personal access token for the user
            $token = $user->createToken($request->email)->plainTextToken;

            // Return success response with the user's details and token
            return $this->sendSuccess(['user' => $user, 'token' => $token], 'User logged in successfully');

        } catch (\Exception $e) {
            // Log  and return any exception that occurs during the login process
            Log::error('Error logging in user: ' . $e->getMessage());
            return $this->sendError([], 'An error occurred while logging in the user', 500);
        }
    }

    // Logout request
    public function logout(Request $request)
    {
        try {
            // Retrieve the authenticated user
            $user = User::where('id', Auth::user()->id)->first();

            if ($user) {
                // Delete all tokens for the authenticated user
                $user->tokens()->delete();

                // Return success response
                return $this->sendSuccess([], 'User logged out successfully');
            } else {
                // Return error response if user is not authenticated
                return $this->sendError('User is not authenticated', 401);
            }
        } catch (\Exception $e) {
            // Log and return any exception that occurs during the logout process
            Log::error('Error logging out user: ' . $e->getMessage());
            return $this->sendError([], 'An error occurred while logging out the user', 500);
        }
    }
}
