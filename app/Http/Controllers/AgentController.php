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
    public function AgentLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $notification = [
            'message' => 'Agent Logout Successfully',
            'alert-type' => 'success'
        ];
        return redirect('/agent/login')->with($notification);
    } // End Method 

    public function AgentProfile()
    {
        // Get the authenticated user's ID and find the corresponding record
        $id = Auth::id();
        $profileData = User::findOrFail($id); // Use `findOrFail` for safety if user data is critical
        return view('agent.agent_profile_view', compact('profileData'));
    } // End Method 

    public function AgentProfileUpdate(Request $request)
    {
        // Get the authenticated user's ID and find the corresponding record
        $id = Auth::id();
        $data = User::findOrFail($id); // Use `findOrFail` for safety if user data is critical

        // Validate the request data
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15|unique:users,phone,' . $id,
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user data
        $data->fill($validatedData);

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            // Remove the old photo if it exists
            if ($data->photo && file_exists(public_path('upload/agent_images/' . $data->photo))) {
                unlink(public_path('upload/agent_images/' . $data->photo));
            }

            // Save the new photo
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/agent_images'), $filename);
            $data->photo = $filename; // Update the photo attribute
        }

        // Save the updated user data
        $data->save();

        // Return a success notification
        return redirect()->back()->with([
            'message' => 'Agent Profile Updated Successfully',
            'alert-type' => 'success',
        ]);
    } // End Method

    public function AgentChangePassword()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('agent.agent_change_password', compact('profileData'));
    } // End Method 

    public function AgentUpdatePassword(Request $request)
    {
        // Validate input fields
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed', // Enforce a minimum length and require confirmation
        ]);

        $user = Auth::user(); // Fetch the authenticated user

        // Check if the provided old password matches the current password
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with([
                'message' => 'Old Password does not match!',
                'alert-type' => 'error',
            ]);
        }

        // Update the password securely
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Optionally log the user out after a password update for added security
        Auth::logout();

        // Notify the user and redirect them to the login page
        return redirect()->route('login')->with([
            'message' => 'Password changed successfully! Please log in with your new password.',
            'alert-type' => 'success',
        ]);
    }
}
