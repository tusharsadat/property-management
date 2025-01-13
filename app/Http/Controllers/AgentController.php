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
        return view('agent.agent_dashboard');
    } // End Method 

    public function AgentLogin()
    {
        return view('agent.agent_login');
    } // End Method 

    public function AgentRegister(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
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
