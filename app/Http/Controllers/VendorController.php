<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Vendor;
use App\VendorCategory;
use App\Http\Requests\Vendor\CreateRequest;
use App\Http\Requests\Vendor\CreateCategoryRequest;
use App\Http\Requests\Vendor\DeleteRequest;
use App\Http\Requests\Vendor\DeleteCategoryRequest;
use App\Http\Requests\Vendor\GetCategoriesRequest;
use App\Http\Requests\Vendor\GetCategoryRequest;
use App\Http\Requests\Vendor\GetVendorRequest;
use App\Http\Requests\Vendor\GetVendorsRequest;
use App\Http\Requests\Vendor\UpdateRequest;
use App\Http\Requests\Vendor\UpdateCategoryRequest;

/**
 * @resource Vendors
 *
 * This module handles access to vendor details.
 *
 */
class VendorController extends Controller
{
    public function create(CreateRequest $request)
    {
        $vendor = new Vendor;

        $values = ['name', 'phone', 'email', 'lat',
                   'lng', 'slots', 'description',
                   'website', 'cost', 'cost_terms'];

        foreach ($values as $value) {

            if ($request->has($value))
                $vendor->$value = $request->get($value);
        }

        if ($request->hasFile('image')) {

            $fileName = time().'_'.rand(1, 100).'.'.$request->file('image')->guessExtension();
            $request->file('image')->move('storage/uploads', $fileName);
            $vendor->image_url = $fileName;
        }

        $vendor->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'vendor successfully created',
            'id'                => $vendor->id,
        ], Response::HTTP_OK);

    }

    public function update(UpdateRequest $request)
    {
        $vendor = NULL;
        if (! ($vendor = Vendor::find($request->get('vendor_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'vendor not found for the id given',
            ], Response::HTTP_NOT_FOUND);
        }

        $values = ['name', 'phone', 'email', 'lat',
                   'lng', 'slots', 'description',
                   'website', 'cost', 'cost_terms'];

        foreach ($values as $value) {

            if ($request->has($value))
                $vendor->$value = $request->get($value);
        }

        if ($request->hasFile('image')) {

            Storage::delete('storage/uploads/', $vendor->image_url);
            $fileName = time().'_'.rand(1, 100).'.'.$request->file('image')->guessExtension();
            $request->file('image')->move('storage/uploads', $fileName);
            $vendor->image_url = $fileName;
        }

        $vendor->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'vendor successfully updated',
        ], Response::HTTP_OK);
    }

    /**
     * Get vendors under category.
     *
     * This returns a list of vendors paginated for a given category.
     */
    public function getPaginated(GetVendorsRequest $request)
    {
        $category = NULL;

        if (! ($category = VendorCategory::find($request->get('category_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'category not found for the id given',
            ], Response::HTTP_NOT_FOUND);
        }

        $vendors = $category->vendors()
                            ->paginate(config('const.page.small'))
                            ->toArray();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully got vendors',
            'vendors'           => $vendors,
        ], Response::HTTP_OK);
    }

    /**
     * Get one vendor.
     *
     * Returns one vendor details, given their id.
     */
    public function getOne(GetVendorRequest $request)
    {
        $vendor = NULL;
        if (! ($vendor = Vendor::find($request->get('vendor_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'vendor not found for the id given',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully got categories',
            'category'          => $vendor->toArray(),
        ], Response::HTTP_OK);
    }

    public function deleteVendor(DeleteRequest $request)
    {
        $vendor = NULL;
        if (! ($vendor = Vendor::find($request->get('vendor_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'vendor not found for the id given',
            ], Response::HTTP_NOT_FOUND);
        }

        $vendor->trash();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully delete vendor',
        ], Response::HTTP_OK);
    }


    public function createCategory(CreateCategoryRequest $request)
    {
        $category = new VendorCategory;

        $category->name         = $request->get('name');
        $category->has_name     = $request->get('has_name');
        $category->has_image    = $request->get('has_image');
        $category->phone        = $request->get('phone');
        $category->email        = $request->get('email');
        $category->location     = $request->get('location');
        $category->slots        = $request->get('slots');
        $categroy->description  = $request->get('description');
        $category->rating       = $request->get('rating');
        $category->website      = $request->get('website');
        $category->cost         = $request->get('cost');
        $category->cost_terms   = $request->get('cost_terms');

        if ($request->hasFile('image')) {

            $fileName = time().'_'.rand(1, 100).'.'.$request->file('image')->guessExtension();
            $request->file('image')->move('storage/uploads', $fileName);
            $category->image_url = $fileName;
        }

        $category->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'category successfully created',
            'id'                => $category->id
        ], Response::HTTP_OK);
    }

    public function updateCategory(UpdateCategoryRequest $request)
    {
        $category = NULL;

        if (! ($category = VendorCategory::find($request->get('category_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'category not found for the id given',
            ], Response::HTTP_NOT_FOUND);
        }

        $category->name         = $request->get('name');
        $category->has_name     = $request->get('has_name');
        $category->has_image    = $request->get('has_image');
        $category->phone        = $request->get('phone');
        $category->email        = $request->get('email');
        $category->location     = $request->get('location');
        $category->slots        = $request->get('slots');
        $categroy->description  = $request->get('description');
        $category->rating       = $request->get('rating');
        $category->website      = $request->get('website');
        $category->cost         = $request->get('cost');
        $category->cost_terms   = $request->get('cost_terms');

        if ($request->hasFile('image')) {
            Storage::delete('storage/uploads/' . $category->image_url);
            $fileName = time().'_'.rand(1, 100).'.'.$request->file('image')->guessExtension();
            $request->file('image')->move('storage/uploads', $fileName);
            $category->image_url = $fileName;
        }

        $category->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'category successfully updated',
        ], Response::HTTP_OK);
    }

    /**
     * Get vendor categories.
     *
     * This returns the list of all vendors currently available.
     */
    public function getPaginatedCategories(GetCategoriesRequest $request)
    {
        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully got categories',
            'categories'        => VendorCategory::all()->toArray(),
        ], Response::HTTP_OK);
    }

    /**
     * Get one vendor category
     *
     * Returns one vendor category details.
     */
    public function getOneCategory(GetCategoryRequest $request)
    {
        $category = NULL;

        if (! ($category = VendorCategory::find($request->get('category_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'category not found for the id given',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully got categories',
            'category'          => $category->toArray(),
        ], Response::HTTP_OK);
    }

    public function deleteCategory(DeleteCategoryRequest $request)
    {
        $category = NULL;

        if (! ($category = VendorCategory::find($request->get('category_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'category not found for the id given',
            ], Response::HTTP_NOT_FOUND);
        }

        $category->trash();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully deleted category',
        ], Response::HTTP_OK);
    }
}
