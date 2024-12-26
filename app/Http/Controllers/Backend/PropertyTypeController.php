<?php

namespace App\Http\Controllers\Backend;

use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PropertyTypeController extends Controller
{
    public function AllType()
    {
        $types = PropertyType::latest()->get();
        return view('backend.all_property_type', compact('types'));
    } // End Method 

    public function AddType()
    {
        return view('backend.add_property_type');
    } // End Method 

    public function StoreType(Request $request)
    {
        // Validation 
        $request->validate([
            'type_name' => 'required|unique:property_types|max:200',
            'type_icon' => 'required'
        ]);
        PropertyType::insert([
            'type_name' => $request->type_name,
            'type_icon' => $request->type_icon,
        ]);
        $notification = array(
            'message' => 'Property Type Create Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.type')->with($notification);
    } // End Method 

    public function EditType($id)
    {
        $types = PropertyType::findOrFail($id);
        return view('backend.edit_property_type', compact('types'));
    } // End Method

    public function UpdateType(Request $request)
    {
        $pid = $request->id;

        PropertyType::findOrFail($pid)->update([
            'type_name' => $request->type_name,
            'type_icon' => $request->type_icon,
        ]);
        $notification = array(
            'message' => 'Property Type Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.type')->with($notification);
    } // End Method 

    public function DeleteType($id)
    {
        PropertyType::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Property Type Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // End Method 
}
