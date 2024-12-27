<?php

namespace App\Http\Controllers\Backend;

use App\Models\Amenities;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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



    ///////////// Amenitites All Method //////////////

    public function AllAmenitie()
    {
        $amenities = Amenities::latest()->get();
        return view('backend.amenities.all_amenities', compact('amenities'));
    } // End Method 

    public function AddAmenitie()
    {
        return view('backend.amenities.add_amenities');
    } // End Method 

    public function checkAmenitiesName(Request $request)
    {
        $exists = DB::table('amenities')->where('amenities_name', $request->amenities_name)->exists();

        return response()->json(!$exists); // Return true if not exists, false if it exists
    } // End Method

    public function StoreAmenitie(Request $request)
    {

        //Validation 
        $request->validate([
            'amenities_name' => 'required|unique:amenities|max:200',
        ]);

        Amenities::insert([
            'amenities_name' => $request->amenities_name,
        ]);
        $notification = array(
            'message' => 'Amenities Create Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.amenitie')->with($notification);
    } // End Method 

    public function EditAmenitie($id)
    {
        $amenities = Amenities::findOrFail($id);
        return view('backend.amenities.edit_amenities', compact('amenities'));
    } // End Method 

    public function UpdateAmenitie(Request $request)
    {
        //Validation 
        $request->validate([
            'amenities_name' => 'required|unique:amenities|max:200',
        ]);

        $ame_id = $request->id;

        Amenities::findOrFail($ame_id)->update([
            'amenities_name' => $request->amenities_name,
        ]);
        $notification = array(
            'message' => 'Amenities Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.amenitie')->with($notification);
    } // End Method 

    public function DeleteAmenitie($id)
    {
        Amenities::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Amenities Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // End Method 
}
