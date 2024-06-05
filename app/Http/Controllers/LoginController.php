<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function index()
    {
        return view('account.auth.login');
    }
    public function authenticate(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
        
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        
            // Attempt to authenticate the user
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // Check user's role
                if (Auth::user()->role != "customer") {
                    Auth::logout();
                    return redirect()->route('account.login')->with('error', 'You are not authorized to access this page');
                }
                // Redirect to profile page upon successful authentication
                return redirect()->route('account.dashboard');
            }
        
            // Authentication failed
            return redirect()->route('account.login')->with('error', 'Either Email/Password is incorrect.');
        } catch (ValidationException $e) {
            return redirect()->route('account.login')->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error authenticating user: ' . $e->getMessage());
        
            // Handle the error
            return redirect()->route('account.login')->with('error', 'An error occurred while authenticating. Please try again.');
        }
    }

    public function register()
    {
        return view('account.auth.register');
    }

    public function processRegister(Request $request)
    {

        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
            ]);
        
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        
            // Create a new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
            ]);
        
            // Redirect to login page with success message
            return redirect()->route('account.login')->with('success', 'You have successfully registered.');
        } catch (ValidationException $e) {
            return redirect()->route('account.register')->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error registering user: ' . $e->getMessage());
        
            // Handle the error
            return redirect()->route('account.register')->with('error', 'An error occurred while registering. Please try again.');
        }
    }

    public function profile()
    {
        $user = User::find(Auth::user()->id);
        return view('account.auth.profile',compact('user'));
    }
    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users,email,' . Auth::id(),
            ]);

            if ($validator->fails()) {
                return redirect()->route('account.profile')->withInput()->withErrors($validator);
            }

            // Retrieve the authenticated user
            $user = User::where('id', Auth::user()->id)->first();

            // Update the user's profile
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return redirect()->route('account.profile')->with('success', 'Profile updated successfully.');
        } catch (ModelNotFoundException $e) {
            // Log the error
            Log::error('User not found: ' . $e->getMessage());

            // Handle the error
            return redirect()->route('account.profile')->with('error', 'User not found. Please try again.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating profile: ' . $e->getMessage());
            return redirect()->route('account.profile')->with('error', 'An error occurred while updating the profile.');
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }

}
