<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Package;
use App\Models\Facility;
use App\Models\Property;
use App\Models\Amenities;
use App\Models\MultiImage;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Models\PropertyMessage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Intervention\Image\Decoders\FilePathImageDecoder;

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

        $multiImages = $request->file('multi_img');

        foreach ($multiImages as $img) {
            // create image manager with desired driver
            $manager = new ImageManager(new Driver());
            // read image from file system
            $images = $manager->read($img);
            $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
            $images->resize(770, 520);
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

    public function EditProperty($id)
    {

        $property = Property::findOrFail($id);

        $facilities = Facility::where('property_id', $id)->get();
        $type = $property->amenities_id;
        $property_ami = explode(',', $type);

        $multiImage = MultiImage::where('property_id', $id)->get();
        $propertytype = PropertyType::latest()->get();
        $amenities = Amenities::latest()->get();
        $activeAgent = User::where('status', 'active')->where('role', 'agent')->latest()->get();

        return view('backend.property.edit_property', compact('property', 'propertytype', 'amenities', 'activeAgent', 'property_ami', 'multiImage', 'facilities'));
    } // End Method 

    public function UpdateProperty(Request $request)
    {
        $amen = $request->amenities_id;
        $amenites = implode(",", $amen);
        $property_id = $request->id;
        Property::findOrFail($property_id)->update([
            'ptype_id' => $request->ptype_id,
            'amenities_id' => $amenites,
            'property_name' => $request->property_name,
            'property_slug' => strtolower(str_replace(' ', '-', $request->property_name)),
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
            'updated_at' => Carbon::now(),
        ]);
        $notification = array(
            'message' => 'Property Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.property')->with($notification);
    } // End Method

    public function UpdatePropertyThambnail(Request $request)
    {
        $pro_id = $request->id;
        $oldImage = $request->old_img;
        $getimage = $request->file('property_thambnail');
        // create image manager with desired driver
        $manager = new ImageManager(new Driver());
        $name_gen = hexdec(uniqid()) . '.' . $getimage->getClientOriginalExtension();
        // read image from file system
        $image = $manager->read($getimage);
        // resize image proportionally to 300px width
        $image->resize(370, 250);
        $save_url = 'upload/property/thambnail/' . $name_gen;

        $image->toJpeg(80)->save(public_path('upload/property/thambnail/' . $name_gen));

        if (file_exists($oldImage)) {
            unlink($oldImage);
        }
        Property::findOrFail($pro_id)->update([
            'property_thambnail' => $save_url,
            'updated_at' => Carbon::now(),
        ]);
        $notification = array(
            'message' => 'Property Image Thambnail Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // End Method

    public function UpdatePropertyMultiimage(Request $request)
    {
        // Retrieve the uploaded multi-images
        $multiImages = $request->file('multi_img');

        // Check if $multiImages is empty
        if (empty($multiImages)) {
            // Image is empty or not provided, return with error message
            $notification = [
                'message' => 'Image cannot be empty',
                'alert-type' => 'error'
            ];

            return redirect()->back()->with($notification);
        }

        // Iterate over each uploaded image
        foreach ($multiImages as $id => $img) {
            // Find the multi-image record by ID
            $imgDel = MultiImage::findOrFail($id);

            // Delete the old image file from storage
            if (file_exists(public_path($imgDel->photo_name))) {
                unlink(public_path($imgDel->photo_name));
            }

            // Create an image manager with desired driver
            $manager = new ImageManager(new Driver());

            // Read the current image from the file
            $image = $manager->read($img);

            // Generate a unique filename for the image
            $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();

            // Resize and save the image
            $image->resize(770, 520)->save(public_path('upload/property/multi-image/' . $make_name), 100);

            // Define the upload path for the new image
            $uploadPath = 'upload/property/multi-image/' . $make_name;

            // Update the multi-image record with the new image path and timestamp
            MultiImage::where('id', $id)->update([
                'photo_name' => $uploadPath,
                'updated_at' => Carbon::now(),
            ]);
        }

        // Set the success notification
        $notification = [
            'message' => 'Property Multi Image Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function PropertyMultiImageDelete($id)
    {
        $multiImage = MultiImage::findOrFail($id);

        // Delete the image file
        if (file_exists(public_path($multiImage->photo_name))) {
            unlink(public_path($multiImage->photo_name));
        }

        // Remove database entry
        $multiImage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully!'
        ]);
    } // End Method 

    public function StoreNewMultiimage(Request $request)
    {
        $newMulti = $request->imageid;
        $multiImages = $request->file('multi_img');
        // create image manager with desired driver
        $manager = new ImageManager(new Driver());
        // read image from file system
        $images = $manager->read($multiImages);
        $make_name = hexdec(uniqid()) . '.' . $multiImages->getClientOriginalExtension();
        $images->resize(770, 520);
        $images->toJpeg(100)->save(public_path('upload/property/multi-image/' . $make_name));
        $uploadPath = 'upload/property/multi-image/' . $make_name;

        MultiImage::insert([
            'property_id' => $newMulti,
            'photo_name' => $uploadPath,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Property Multi Image Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // End Method 

    public function UpdatePropertyFacilities(Request $request)
    {
        $pid = $request->id;
        if ($request->facility_name == NULL) {
            return redirect()->back();
        } else {
            Facility::where('property_id', $pid)->delete();
            $facilities = Count($request->facility_name);

            for ($i = 0; $i < $facilities; $i++) {
                $fcount = new Facility();
                $fcount->property_id = $pid;
                $fcount->facility_name = $request->facility_name[$i];
                $fcount->distance = $request->distance[$i];
                $fcount->save();
            } // end for 
        }
        $notification = array(
            'message' => 'Property Facility Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // End Method 

    public function DeleteProperty($id)
    {
        // Retrieve the property record
        $property = Property::findOrFail($id);

        // Delete property thumbnail if it exists
        if (file_exists($property->property_thambnail)) {
            unlink($property->property_thambnail);
        }

        // Delete the property record
        $property->delete();

        // Retrieve and delete related multi-images in bulk
        $multiImages = MultiImage::where('property_id', $id)->get();

        foreach ($multiImages as $img) {
            if (file_exists($img->photo_name)) {
                unlink($img->photo_name);
            }
        }
        MultiImage::where('property_id', $id)->delete(); // Bulk delete

        // Delete related facilities in bulk
        Facility::where('property_id', $id)->delete();

        // Success notification
        $notification = [
            'message' => 'Property and related data deleted successfully',
            'alert-type' => 'success',
        ];

        // Redirect back with a notification
        return redirect()->back()->with($notification);
    } // End Method  

    public function DetailsProperty($id)
    {

        $property = Property::findOrFail($id);
        $type = $property->amenities_id;
        $property_ami = explode(',', $type);
        $facilities = Facility::where('property_id', $id)->get();
        $multiImage = MultiImage::where('property_id', $id)->get();
        $propertytype = PropertyType::latest()->get();
        $amenities = Amenities::latest()->get();
        $activeAgent = User::where('status', 'active')->where('role', 'agent')->latest()->get();
        return view('backend.property.details_property', compact('property', 'propertytype', 'amenities', 'activeAgent', 'property_ami', 'multiImage', 'facilities'));
    } // End Method 

    public function InactiveProperty(Request $request)
    {

        $pid = $request->id;
        Property::findOrFail($pid)->update([
            'status' => 0,
        ]);
        $notification = [
            'message' => 'Property Inactive Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.property')->with($notification);
    } // End Method 
    public function ActiveProperty(Request $request)
    {
        $pid = $request->id;
        Property::findOrFail($pid)->update([
            'status' => 1,
        ]);
        $notification = [
            'message' => 'Property Active Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.property')->with($notification);
    } // End Method 

    public function AdminPackageHistory()
    {
        $packageHistory = Package::latest()->get();
        return view('backend.package.package_history', compact('packageHistory'));
    } // End Method 

    public function DownloadPackageInvoice($id)
    {
        // Fetch the package history based on the given ID
        $packageHistory = Package::find($id);

        // Handle invalid or missing package history
        if (!$packageHistory) {
            return redirect()->route('admin.package.history')->with([
                'message' => 'Package not found or invalid ID.',
                'alert-type' => 'error',
            ]);
        }

        try {
            // Load the PDF view and generate the invoice
            $pdf = Pdf::loadView('backend.package.package_history_invoice', compact('packageHistory'))
                ->setPaper('a4')
                ->setOption([
                    'tempDir' => public_path(),
                    'chroot' => public_path(),
                ]);

            // Return the generated PDF as a downloadable response
            return $pdf->download('invoice_' . $packageHistory->id . '.pdf');
        } catch (\Exception $e) {
            // Handle PDF generation errors
            return redirect()->route('admin.package.history')->with([
                'message' => 'Failed to generate invoice. Please try again.',
                'alert-type' => 'error',
            ]);
        }
    } // End Method 
    public function AdminPropertyMessage()
    {

        $usermsg = PropertyMessage::latest()->get();
        return view('backend.message.all_message', compact('usermsg'));
    } // End Method  
    public function PropertyMessageDetails($messageId)
    {
        // Fetch all messages in descending order
        $userMessages = PropertyMessage::latest()->get();

        // Fetch the specific message details
        $messageDetails = PropertyMessage::findOrFail($messageId);

        return view('backend.message.message_details', compact('userMessages', 'messageDetails'));
    }
}
