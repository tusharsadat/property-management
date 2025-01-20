<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        return view('admin.index');
    } // End Method 

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Admin Logout Successfully',
            'alert-type' => 'success'
        );

        return redirect('/admin/login')->with($notification);
    } // End Method

    public function AdminLogin()
    {
        return view('admin.admin_login');
    } // End Method 

    public function AdminProfile()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_profile_view', compact('profileData'));
    } // End Method 

    public function AdminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/' . $data->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $data['photo'] = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // End Method

    public function AdminChangePassword()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_change_password', compact('profileData'));
    } // End Method 

    public function AdminUpdatePassword(Request $request)
    {
        // Validation 
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);
        // Match The Old Password
        if (!Hash::check($request->old_password, auth::user()->password)) {

            $notification = array(
                'message' => 'Old Password Does not Match!',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
        /// Update The New Password 
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
        $notification = array(
            'message' => 'Password Change Successfully',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    } // End Method 


    /////////// Agent User All Method ////////////

    public function AllAgent()
    {
        $allagent = User::where('role', 'agent')->get();
        return view('backend.agentuser.all_agent', compact('allagent'));
    } // End Method 

    public function AddAgent()
    {
        return view('backend.agentuser.add_agent');
    } // End Method 

    public function validateEmail(Request $request)
    {
        $emailExists = User::where('email', $request->email)->exists();
        return response()->json(!$emailExists); // Return true if not exists, false otherwise
    } // End Method 

    public function validatePhone(Request $request)
    {
        $phoneExists = User::where('phone', $request->phone)->exists();
        return response()->json(!$phoneExists); // Return true if not exists, false otherwise
    } // End Method 

    public function StoreAgent(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:15|unique:users,phone',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Store new agent in the database
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'] ?? null, // Nullable field
            'password' => Hash::make($validatedData['password']),
            'role' => User::ROLE_AGENT, // Use constant for role
            'status' => User::STATUS_ACTIVE, // Use constant for status
        ]);

        // Success notification
        $notification = [
            'message' => 'Agent Created Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.agent')->with($notification);
    } // End Method

    public function EditAgent($id)
    {
        $allagent = User::findOrFail($id);
        return view('backend.agentuser.edit_agent', compact('allagent'));
    } // End Method 
}
