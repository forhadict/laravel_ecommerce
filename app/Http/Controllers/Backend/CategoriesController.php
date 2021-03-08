<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return view('backend.pages.categories.index', compact('categories'));
    }

    public function create()
    {
        $main_categories = Category::orderBy('name', 'desc')->where('parent_id', NULL)->get();
        return view('backend.pages.categories.create', compact('main_categories'));
    }


    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'image' => 'mimes:jpeg,jpg,png,gif|nullable|max:100000',
            ],
            [
                'name.required' => 'Please provide a category name',
                'image.image' => 'Please provide a valid image format',
            ]
        );

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        // Image Insert
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $img = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/categories/' . $img);
            Image::make($image)->save($location);
            $category->image = $img;
        }
        $category->save();

        session()->flash('success', 'Category added successfully');
        return redirect()->route('admin.categories');
    }


    public function edit($id)
    {
        $main_categories = Category::orderBy('name', 'desc')->where('parent_id', NULL)->get();
        $category = Category::find($id);
        if (!is_null($category)) {
            return view('backend.pages.categories.edit', compact('category', 'main_categories'));
        } else {
            return redirect()->route('admin.categories');
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'image' => 'mimes:jpeg,jpg,png,gif|nullable|max:100000',
            ],
            [
                'name.required' => 'Please provide a category name',
                'image.image' => 'Please provide a valid image format',
            ]
        );

        $category = Category::find($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        // Image Insert
        if ($request->hasFile('image')) {
            if (File::exists('images/categories/' . $category->image)) {
                File::delete('images/categories/' . $category->image);
            }

            $image = $request->file('image');
            $img = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/categories/' . $img);
            Image::make($image)->save($location);
            $category->image = $img;
        }
        $category->save();

        session()->flash('success', 'Category updated successfully');
        return redirect()->route('admin.categories');
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if (!is_null($category)) {
            // If it is parent category, then delete all its sub category
            if ($category->parent_id == NULL) {
                // Delete sub categories
                $sub_categories = Category::orderBy('name', 'desc')->where('parent_id', $category->id)->get();

                foreach ($sub_categories as $sub_category) {
                    // Delete category image
                    if (File::exists('images/categories/' . $sub_category->image)) {
                        File::delete('images/categories/' . $sub_category->image);
                    }
                    $sub_category->delete();
                }
            }

            // Delete category image
            if (File::exists('images/categories/' . $category->image)) {
                File::delete('images/categories/' . $category->image);
            }
            $category->delete();
        }
        session()->flash('success', 'Category deleted successfully');
        return back();
    }
}
