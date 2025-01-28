<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class IndexController extends Controller
{
    public function PropertyDetails($id, $slug)
    {
        // Retrieve the property and related data using eager loading
        $property = Property::with(['multiImages', 'facilities', 'property_type'])
            ->findOrFail($id);

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
        ]);
    } // End Method 
}
