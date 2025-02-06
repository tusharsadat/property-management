<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Property;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PropertyMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function PropertyDetails($id, $slug)
    {
        // Retrieve the property and related data using eager loading
        $property = Property::with(['multiImages', 'facilities', 'property_type'])
            ->findOrFail($id);

        // Fetch related properties of the same type, excluding the current property
        $relatedProperty = Property::where('ptype_id', $property->ptype_id)
            ->where('id', '!=', $id)
            ->latest()
            ->limit(3)
            ->get();

        // Convert YouTube video URL to embed link
        if (Str::contains($property->property_video, 'watch?v=')) {
            $property->property_video = Str::replace('watch?v=', 'embed/', $property->property_video);
        }

        // Extract amenities and split them into an array
        $property_amen = $property->amenities_id ? explode(',', $property->amenities_id) : [];

        // Pass data to the view
        return view('frontend.property.property_details', [
            'property' => $property,
            'multiImage' => $property->multiImages,
            'property_amen' => $property_amen,
            'facility' => $property->facilities,
            'relatedProperty' => $relatedProperty,
        ]);
    } // End Method 
    public function PropertyMessage(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'agent_id' => 'required|exists:users,id',
            'msg_name' => 'required|string|max:255',
            'msg_email' => 'required|email|max:255',
            'msg_phone' => 'required|string|max:15',
            'message' => 'required|string|max:1000',
        ]);

        // Check if the user is authenticated
        if (Auth::check()) {
            // Create a new property message
            PropertyMessage::create([
                'user_id' => Auth::id(),
                'agent_id' => $request->agent_id,
                'property_id' => $request->property_id,
                'msg_name' => $request->msg_name,
                'msg_email' => $request->msg_email,
                'msg_phone' => $request->msg_phone,
                'message' => $request->message,
            ]);

            // Return a JSON success response
            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully!',
            ]);
        }

        // Return a JSON error response
        return response()->json([
            'status' => 'error',
            'message' => 'Please login to send a message.',
        ], 401);
    } // End Method 
    public function AgentDetails($id)
    {
        $agent = User::findOrFail($id);
        return view('frontend.agent.agent_details', compact('agent'));
    } // End Method 
}
