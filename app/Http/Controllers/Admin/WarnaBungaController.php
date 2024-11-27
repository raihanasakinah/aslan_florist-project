<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WarnaBunga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class WarnaBungaController extends Controller
{
    public function warnabunga() {
        // Correcting issues in the Skydash Admin Panel Sidebar using Session
        Session::put('page', 'warnabunga');

        $categories = WarnaBunga::with(['section', 'parentWarnaBunga'])->get()->toArray();
        // dd($categories);
        // foreach ($categories as $category) {
        //     dd($category['section']['name']);
        // }


        return view('admin.warnabungas.warnabungas')->with(compact('warnabungas'));
    }

    public function updateWarnaBungaStatus(Request $request) { // Update Category Status using AJAX in categories.blade.php
        if ($request->ajax()) { // if the request is coming via an AJAX call
            $data = $request->all(); // Getting the name/value pairs array that are sent from the AJAX request (AJAX call)
            // dd($data);

            if ($data['status'] == 'Active') { // $data['status'] comes from the 'data' object inside the $.ajax() method    // reverse the 'status' from (ative/inactive) 0 to 1 and 1 to 0 (and vice versa)
                $status = 0;
            } else {
                $status = 1;
            }


            warnabunga::where('id', $data['warnabunga_id'])->update(['status' => $status]); // $data['category_id'] comes from the 'data' object inside the $.ajax() method in admin/js/custom.js
            // echo '<pre>', var_dump($data), '</pre>';

            return response()->json([ // JSON Responses: https://laravel.com/docs/9.x/responses#json-responses
                'status'      => $status,
                'warnabunga_id' => $data['warnabunga_id']
            ]);
        }
    }

    public function addEditWarnaBunga(Request $request, $id = null) { // If the $id is not passed, this means Add a Category, if not, this means Edit the Category
        // Correcting issues in the Skydash Admin Panel Sidebar using Session
        Session::put('page', 'warnabunga');


        if ($id == '') { // if there's no $id is passed in the route/URL parameters, this means Add a new Category
            $title = 'Add WarnaBunga';
            $warnabunga = new WarnaBunga();
            // dd($category);

            $getWarnaBunga = array(); // An array that contains all the parent categories that are under this section

            $message = 'WarnaBunga added successfully!';
        } else { // if the $id is passed in the route/URL parameters, this means Edit the Category
            $title = 'Edit WarnaBunga';
            $warnabunga = WarnaBunga::find($id);
            // dd($category->parentCategory);

            $getWarnaBunga = WarnaBunga::with('subWarnaBunga')->where([ // $getCategories are all the parent categories, and their child categories
                // $getCategories is the parent categories (with no parents i.e. parent_id is 0 zero) but having the subCategories (the categories that they're parent to) at the same time
                'parent_id'  => 0, // parent_id is 0 zero BECAUSE IT'S A PARENT CATEGORY
                'section_id' => $warnabunga['section_id']
            ])->get();


            $message = 'WarnaBunga updated successfully!';
        }


        if ($request->isMethod('post')) { // WHETHER Add or Update <form> submission!!
            $data = $request->all();
            // dd($data);


            // Laravel's Validation    // Customizing Laravel's Validation Error Messages: https://laravel.com/docs/9.x/validation#customizing-the-error-messages    // Customizing Validation Rules: https://laravel.com/docs/9.x/validation#custom-validation-rules
            $rules = [
                'warnabunga_name' => 'required|regex:/^[\pL\s\-]+$/u', // only alphabetical characters and spaces
                'section_id'    => 'required',
                'url'           => 'required',
            ];

            $customMessages = [ // Specifying A Custom Message For A Given Attribute: https://laravel.com/docs/9.x/validation#specifying-a-custom-message-for-a-given-attribute
                'warnabunga_name.required' => 'WarnaBunga Name is required',
                'warnabunga_name.regex'    => 'Valid WarnaBunga Name is required',
                'section_id.required'    => 'Section is required',
                'url.required'           => 'WarnaBunga URL is required',
            ];

            $this->validate($request, $rules, $customMessages);


            if ($data['warnabunga_discount'] == '') {
                $data['warnabunga_discount'] = 0;
            }


            // Uploading Category Image    // Using the Intervention package for uploading images
            if ($request->hasFile('warnabunga_image')) { // the HTML name attribute    name="admin_name"    in update_admin_details.blade.php
                $image_tmp = $request->file('warnabunga_image'); // Retrieving Uploaded Files: https://laravel.com/docs/9.x/requests#retrieving-uploaded-files
                if ($image_tmp->isValid()) {
                    // Get the image extension
                    $extension = $image_tmp->getClientOriginalExtension();

                    // Generate a random name for the uploaded image (to avoid that the image might get overwritten if its name is repeated)
                    $imageName = rand(111, 99999) . '.' . $extension;

                    // Assigning the uploaded images path inside the 'public' folder
                    $imagePath = 'front/images/warnabunga_images/' . $imageName;

                    // Upload the image using the 'Intervention' package and save it in our path inside the 'public' folder
                    Image::make($image_tmp)->save($imagePath); // '\Image' is the Intervention package

                    // Insert the image name in the database table
                    $warnabunga->warnabunga_image = $imageName;
                }

            } else { // In case the admins updates other fields but doesn't update the image itself (doesn't upload a new image), and originally there wasn't any image uploaded in the first place
                $warnabunga->warnabunga_image = '';
            }


            $warnabunga->section_id        = $data['section_id'];
            $warnabunga->parent_id         = $data['parent_id'];
            $warnabunga->warnabunga_name     = $data['warnabunga_name'];
            $warnabunga->warnabunga_discount = $data['warnabunga_discount'];
            $warnabunga->description       = $data['description'];
            $warnabunga->url               = $data['url'];
            // $category->meta_title        = $data['meta_title'];
            // $category->meta_description  = $data['meta_description'];
            // $category->meta_keywords     = $data['meta_keywords'];
            $warnabunga->status            = 1;

            $warnabunga->save(); // Save all data in the database

            return redirect('admin/warnabunga')->with('success_message', $message);
        }


        // Get all sections
        // $getSections = Section::get()->toArray();
        // dd($getSections);


        return view('admin.warnabungas.app_edit_warnabunga')->with(compact('title', 'warnabunga', 'getSections', 'getWarnaBunga'));
    }

    public function appendWarnaBungaLevel(Request $request) { // (AJAX) Show Categories <select> <option> depending on the chosen Section (show the relevant categories of the chosen section) using AJAX in admin/js/custom.js in append_categories_level.blade.php page
        // Note: We created the <div> in a separate file in order for the appendCategoryLevel() method inside the CategoryController to be able to return the whole file as a response to the AJAX call in admin/js/custom.js to show the proper/relevant categories <select> box <option> depending on the selected (chosen) Section
        if ($request->ajax()) { // if the request is coming via an AJAX call
            // if ($request->isMethod('get')) {
                $data = $request->all();
                // dd($data);

                $getWarnaBunga = WarnaBunga::with('subWarnaBunga')->where([ // 'subCategories' is the relationship method inside the Category.php model    // $getCategories are all the parent categories, and their child categories
                    'parent_id'  => 0,
                    'section_id' => $data['section_id'] // $data['section_id'] comes from the 'data' object inside the $.ajax() method in admin/js/custom.js
                ])->get();
            // }

            return view('admin.warnabungas.append_warnabunga_level')->with(compact('getWarnaBunga')); // return-ing the WHOLE append_categories_level.blade.php page
        }
    }

    public function deleteWarnaBunga($id) {
        WarnaBunga::where('id', $id)->delete();

        $message = 'WarnaBunga has been deleted successfully!';

        return redirect()->back()->with('success_message', $message);
    }

    public function deleteWarnaBungaImage($id) { // AJAX call from admin/js/custom.js    // Delete the category image from BOTH SERVER (FILESYSTEM) & DATABASE    // $id is passed as a Route Parameter
        // Category image record in the database
        $warnabungaImage = WarnaBunga::select('warnabunga_image')->where('id', $id)->first();
        // dd($categoryImage);

        // Category image path on the server (filesystem)
        $warnabunga_image_path = 'front/images/warnabunga_images/';

        // Delete the category image on server (filesystem) (from the 'category_images' folder)
        if (file_exists($warnabunga_image_path . $warnabungaImage->warnabunga_image)) {
            unlink($warnabunga_image_path . $warnabungaImage->warnabunga_image);
        }

        // Delete the category image name from the `categories` database table (Note: We won't use delete() method because we're not deleting a complete record (entry) (we're just deleting a one column `category_image` value), we will just use update() method to update the `category_image` name to an empty string value '')
        WarnaBunga::where('id', $id)->update(['warnabunga_image' => '']);

        $message = 'WarnaBunga Image has been deleted successfully!';

        return redirect()->back()->with('success_message', $message);
    }
}
