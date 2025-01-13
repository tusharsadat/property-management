<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;


class AgentController extends Controller
{
    public function AgentDashboard()
    {
        return view('agent.index');
    } // End Method 

    public function AgentLogin()
    {
        return view('agent.agent_login');
    } // End Method 

    public function validateEmail(Request $request)
    {
        $emailExists = User::where('email', $request->email)->exists();
        return response()->json(!$emailExists); // Return true if not exists, false otherwise
    }

    public function validatePhone(Request $request)
    {
        $phoneExists = User::where('phone', $request->phone)->exists();
        return response()->json(!$phoneExists); // Return true if not exists, false otherwise
    }

    public function AgentRegister(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Ensure email is unique
            'phone' => 'required|numeric|unique:users,phone', // Ensure phone is unique
            'password' => 'required|string|min:8|confirmed', // Validation for password and confirmation
        ]);

        // Store agent details
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_AGENT, // Use constants or enums for role
            'status' => User::STATUS_INACTIVE, // Use constants or enums for status
        ]);

        // Trigger the Registered event
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        // Redirect with success notification
        return redirect(RouteServiceProvider::AGENT)
            ->with('success', 'Registration successful! You are now logged in as an agent.');
    }
}
