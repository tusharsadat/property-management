<?php

namespace App\Http\Controllers\Backend;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PropertyController extends Controller
{
    public function AllProperty()
    {
        $property = Property::latest()->get();
        return view('backend.property.all_property', compact('property'));
    } // End Method 

    public function AddProperty()
    {
        return view('backend.property.add_property');
    } // End Method 
}
