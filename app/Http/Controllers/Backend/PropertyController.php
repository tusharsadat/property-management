<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Facility;
use App\Models\Property;
use App\Models\Amenities;
use App\Models\MultiImage;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class PropertyController extends Controller
{
    public function AllProperty()
    {
        $property = Property::latest()->get();
        return view('backend.property.all_property', compact('property'));
    } // End Method 

    public function AddProperty()
    {
        $propertytype = PropertyType::latest()->get();
        $amenities = Amenities::latest()->get();
        $activeAgent = User::where('status', 'active')->where('role', 'agent')->latest()->get();

        return view('backend.property.add_property', compact('propertytype', 'amenities', 'activeAgent'));
    } // End Method 

    public function StoreProperty(Request $request)
    {
        $amen = $request->amenities_id;
        $amenites = implode(",", $amen);
        //dd($amenites);

        $pcode = IdGenerator::generate(['table' => 'properties', 'field' => 'property_code', 'length' => 5, 'prefix' => 'PC']);

        $getimage = $request->file('property_thambnail');
        // create image manager with desired driver
        $manager = new ImageManager(new Driver());
        $name_gen = hexdec(uniqid()) . '.' . $getimage->getClientOriginalExtension();
        // read image from file system
        $image = $manager->read($getimage);
        // resize image proportionally to 300px width
        $image->resize(370, 250);

        // $path = base_path('upload/property/thambnail/');
        // if (!file_exists($path)) {
        //     mkdir($path, 0755, true); // Create directory with write permissions
        // }

        $image->toJpeg(80)->save(public_path('upload/property/thambnail/' . $name_gen));

        $save_url = 'upload/property/thambnail/' . $name_gen;

        $property_id = Property::insertGetId([
            'ptype_id' => $request->ptype_id,
            'amenities_id' => $amenites,
            'property_name' => $request->property_name,
            'property_slug' => strtolower(str_replace(' ', '-', $request->property_name)),
            'property_code' => $pcode,
            'property_status' => $request->property_status,
            'lowest_price' => $request->lowest_price,
            'max_price' => $request->max_price,
            'short_descp' => $request->short_descp,
            'long_descp' => $request->long_descp,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'garage' => $request->garage,
            'garage_size' => $request->garage_size,
            'property_size' => $request->property_size,
            'property_video' => $request->property_video,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'neighborhood' => $request->neighborhood,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'featured' => $request->featured,
            'hot' => $request->hot,
            'agent_id' => $request->agent_id,
            'status' => 1,
            'property_thambnail' => $save_url,
            'created_at' => Carbon::now(),
        ]);

        /// Multiple Image Upload From Here ////

        $images = $request->file('multi_img');

        foreach ($images as $img) {
            // create image manager with desired driver
            $manager = new ImageManager(new Driver());
            // read image from file system
            $images = $manager->read($getimage);
            $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
            $images->resize(770, 520);

            // $path = base_path('upload/property/multi-image/');
            // if (!file_exists($path)) {
            //     mkdir($path, 0755, true); // Create directory with write permissions
            // }

            $images->toJpeg(100)->save(public_path('upload/property/multi-image/' . $make_name));
            $uploadPath = 'upload/property/multi-image/' . $make_name;

            MultiImage::insert([
                'property_id' => $property_id,
                'photo_name' => $uploadPath,
                'created_at' => Carbon::now(),
            ]);
        } // End Foreach
        /// End Multiple Image Upload From Here ////

        /// Facilities Add From Here ////
        $facilities = Count($request->facility_name);
        if ($facilities != NULL) {
            for ($i = 0; $i < $facilities; $i++) {
                $fcount = new Facility();
                $fcount->property_id = $property_id;
                $fcount->facility_name = $request->facility_name[$i];
                $fcount->distance = $request->distance[$i];
                $fcount->save();
            }
        }
        /// End Facilities  ////
        $notification = array(
            'message' => 'Property Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.property')->with($notification);
    } //End Mathod
}
