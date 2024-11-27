<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisBunga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class JenisBungaController extends Controller
{
    public function jenisbunga() {
        // Correcting issues in the Skydash Admin Panel Sidebar using Session
        Session::put('page', 'jenisbunga');

        $categories = JenisBunga::with(['section', 'parentJenisBunga'])->get()->toArray();
        // dd($categories);
        // foreach ($categories as $category) {
        //     dd($category['section']['name']);
        // }

        return view('admin.jenisbungas.jenisbungas')->with(compact('jenisbungas'));
    }

    public function updateJenisBungaStatus(Request $request) { // Update Category Status using AJAX in categories.blade.php
        if ($request->ajax()) { // if the request is coming via an AJAX call
            $data = $request->all(); // Getting the name/value pairs array that are sent from the AJAX request (AJAX call)
            // dd($data);

            if ($data['status'] == 'Active') { // $data['status'] comes from the 'data' object inside the $.ajax() method    // reverse the 'status' from (ative/inactive) 0 to 1 and 1 to 0 (and vice versa)
                $status = 0;
            } else {
                $status = 1;
            }

            JenisBunga::where('id', $data['jenisbunga_id'])->update(['status' => $status]); // $data['category_id'] comes from the 'data' object inside the $.ajax() method in admin/js/custom.js
            // echo '<pre>', var_dump($data), '</pre>';

            return response()->json([ // JSON Responses: https://laravel.com/docs/9.x/responses#json-responses
                'status'      => $status,
                'jenisbunga_id' => $data['jenisbunga_id']
            ]);
        }
    }

    public function addEditJenisBunga(Request $request, $id = null) { // If the $id is not passed, this means Add a Category, if not, this means Edit the Category
        // Correcting issues in the Skydash Admin Panel Sidebar using Session
        Session::put('page', 'jenisbunga');

        if ($id == '') { // if there's no $id is passed in the route/URL parameters, this means Add a new Category
            $title = 'Add JenisBunga';
            $jenisbunga = new JenisBunga();
            // dd($category);

            $getJenisBunga = array(); // An array that contains all the parent categories that are under this section

            $message = 'JenisBunga added successfully!';
        } else { // if the $id is passed in the route/URL parameters, this means Edit the Category
            $title = 'Edit JenisBunga';
            $jenisbunga = JenisBunga::find($id);
            // dd($category->parentCategory);

            $getJenisBunga = JenisBunga::with('subJenisBunga')->where([ // $getCategories are all the parent categories, and their child categories
                // $getCategories is the parent categories (with no parents i.e. parent_id is 0 zero) but having the subCategories (the categories that they're parent to) at the same time
                'parent_id'  => 0, // parent_id is 0 zero BECAUSE IT'S A PARENT CATEGORY
                'section_id' => $jenisbunga['section_id']
            ])->get();

            $message = 'JenisBunga updated successfully!';
        }

        if ($request->isMethod('post')) { // WHETHER Add or Update <form> submission!!
            $data = $request->all();
            // dd($data);

            // Laravel's Validation    // Customizing Laravel's Validation Error Messages: https://laravel.com/docs/9.x/validation#customizing-the-error-messages    // Customizing Validation Rules: https://laravel.com/docs/9.x/validation#custom-validation-rules
            $rules = [
                'jenisbunga_name' => 'required|regex:/^[\pL\s\-]+$/u', // only alphabetical characters and spaces
                'section_id'    => 'required',
                'url'           => 'required',
            ];

            $customMessages = [ // Specifying A Custom Message For A Given Attribute: https://laravel.com/docs/9.x/validation#specifying-a-custom-message-for-a-given-attribute
                'jenisbunga_name.required' => 'JenisBunga Name is required',
                'jenisbunga_name.regex'    => 'Valid JenisBunga Name is required',
                'section_id.required'    => 'Section is required',
                'url.required'           => 'JenisBunga URL is required',
            ];

            $this->validate($request, $rules, $customMessages);

            if ($data['jenisbunga_discount'] == '') {
                $data['jenisbunga_discount'] = 0;
            }

            // Uploading Category Image    // Using the Intervention package for uploading images
            if ($request->hasFile('jenisbunga_image')) { // the HTML name attribute    name="admin_name"    in update_admin_details.blade.php
                $image_tmp = $request->file('jenisbunga_image'); // Retrieving Uploaded Files: https://laravel.com/docs/9.x/requests#retrieving-uploaded-files
                if ($image_tmp->isValid()) {
                    // Get the image extension
                    $extension = $image_tmp->getClientOriginalExtension();

                    // Generate a random name for the uploaded image (to avoid that the image might get overwritten if its name is repeated)
                    $imageName = rand(111, 99999) . '.' . $extension;

                    // Assigning the uploaded images path inside the 'public' folder
                    $imagePath = 'front/images/jenisbunga_images/' . $imageName;

                    // Upload the image using the 'Intervention' package and save it in our path inside the 'public' folder
                    Image::make($image_tmp)->save($imagePath); // '\Image' is the Intervention package

                    // Insert the image name in the database table
                    $jenisbunga->jenisbunga_image = $imageName;
                }

            } else { // In case the admins updates other fields but doesn't update the image itself (doesn't upload a new image), and originally there wasn't any image uploaded in the first place
                $jenisbunga->jenisbunga_image = '';
            }

            $jenisbunga->section_id        = $data['section_id'];
            $jenisbunga->parent_id         = $data['parent_id'];
            $jenisbunga->jenisbunga_name     = $data['jenisbunga_name'];
            $jenisbunga->jenisbunga_discount = $data['jenisbunga_discount'];
            $jenisbunga->description       = $data['description'];
            $jenisbunga->url               = $data['url'];
            // $category->meta_title        = $data['meta_title'];
            // $category->meta_description  = $data['meta_description'];
            // $category->meta_keywords     = $data['meta_keywords'];
            $jenisbunga->status            = 1;

            $jenisbunga->save(); // Save all data in the database

            return redirect('admin/jenisbunga')->with('success_message', $message);
        }

        return view('admin.jenisbungas.add_edit_jenisbunga')->with(compact('title', 'jenisbunga', 'getSections', 'getJenisBunga'));
    }

    public function appendJenisBungaLevel(Request $request) { // (AJAX) Show Categories <select> <option> depending on the chosen Section (show the relevant categories of the chosen section) using AJAX in admin/js/custom.js in append_categories_level.blade.php page
        // Note: We created the <div> in a separate file in order for the appendCategoryLevel() method inside the CategoryController to be able to return the whole file as a response to the AJAX call in admin/js/custom.js to show the proper/relevant categories <select> box <option> depending on the selected (chosen) Section
        if ($request->ajax()) { // if the request is coming via an AJAX call
            // if ($request->isMethod('get')) {
                $data = $request->all();
                // dd($data);

                $getJenisBunga = JenisBunga::with('subJenisBunga')->where([ // 'subCategories' is the relationship method inside the Category.php model    // $getCategories are all the parent categories, and their child categories
                    'parent_id'  => 0,
                    'section_id' => $data['section_id'] // $data['section_id'] comes from the 'data' object inside the $.ajax() method in admin/js/custom.js
                ])->get();
            // }

            return view('admin.jenisbungas.append_jenisbunga_level')->with(compact('getJenisBunga')); // return-ing the WHOLE append_categories_level.blade.php page
        }
    }

    public function deleteJenisBunga($id) {
        JenisBunga::where('id', $id)->delete();

        $message = 'JenisBunga has been deleted successfully!';

        return redirect()->back()->with('success_message', $message);
    }
}
?>
