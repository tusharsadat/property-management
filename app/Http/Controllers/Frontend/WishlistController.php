<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function UserWishlist()
    {

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $userData = Auth::user(); // No need for an extra query

        return view('frontend.dashboard.wishlist', compact('userData'));
    } // End Method

    // Display user's wishlist
    public function GetWishlistProperty()
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized access! Please log in to view your wishlist.'
            ], 401);
        }

        $wishlist = Wishlist::with('property')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $wishQty = Wishlist::where('user_id', Auth::id())->count(); // Count only user's wishlist

        return response()->json([
            'wishlist' => $wishlist,
            'wishQty' => $wishQty,
            'success' => 'Wishlist retrieved successfully!'
        ]);
    }

    // Add a property to wishlist (AJAX)
    public function AddToWishList(Request $request, $property_id)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'You need to log in first!'
            ], 401); // 401 Unauthorized
        }

        // Use firstOrCreate() to avoid an extra query
        $wishlist = Wishlist::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'property_id' => $property_id
            ]

        );

        if ($wishlist->wasRecentlyCreated) {
            return response()->json([
                'success' => 'Successfully added to your wishlist!'
            ]);
        } else {
            return response()->json([
                'error' => 'This property is already in your wishlist!'
            ]);
        }
    } // End Method
}
