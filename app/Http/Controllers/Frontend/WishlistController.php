<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Add a property to wishlist
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
    }
}
