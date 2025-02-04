<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Compare;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CompareController extends Controller
{
    //Add Property to Comparison
    public function AddToCompare(Request $request, $property_id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please log in first.'], 401);
        }

        $user_id = Auth::id();

        if (Compare::where('user_id', $user_id)->where('property_id', $property_id)->exists()) {
            return response()->json(['error' => 'This property is already in your compare list.'], 409);
        }

        Compare::create([
            'user_id' => $user_id,
            'property_id' => $property_id,
        ]);

        return response()->json(['success' => 'Successfully added to your compare list.'], 201);
    } //End method

    public function UserCompare()
    {
        return view('frontend.dashboard.compare');
    } // End Method 

    public function GetCompareProperty()
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Please log in to view your compare list.'
            ], 401);
        }

        $compare = Compare::with('property')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'compare' => $compare,
            'compareCount' => $compare->count(),
            'success' => 'Compare list retrieved successfully!'
        ]);
    } // End Method
    public function CompareRemove($id)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized access. Please log in first.'], 401);
        }

        // Find the Compare entry
        $compareItem = Compare::where('user_id', Auth::id())->where('id', $id)->first();

        if (!$compareItem) {
            return response()->json(['error' => 'Property not found in your compare list.'], 404);
        }

        // Delete the item
        $compareItem->delete();

        return response()->json(['success' => 'Successfully removed the property from your compare list.'], 200);
    }
}
