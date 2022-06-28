<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $subCategories = SubCategory::with('category')->latest()->paginate(20);
            if ($subCategories->count() > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'successful',
                    'data' => $subCategories
                ]);
            }else{
                return response()->json([
                    'status' => 'success',
                    'message' => 'no record found'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => ['required'],
            'name' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->first()
            ]);
        }

        try {
            $saveSubCategory = SubCategory::create($validator->validated());
            if ($saveSubCategory) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'record created',
                    'data' => $saveSubCategory
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $subCategory = SubCategory::with('category')->find($id);
            # check if record is found
            if ($subCategory != null) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'successful',
                    'data' => $subCategory
                ]);
            }else{
                return response()->json([
                    'status' => 'success',
                    'message' => 'record not found',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => ['required'],
            'name' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->first()
            ]);
        }

        try {
            $subCategory = SubCategory::find($id);
            if ($subCategory != null) {
                if ($subCategory->update($validator->validated())) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'record updated',
                        'data' => $subCategory
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 'success',
                    'message' => 'record not found',
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (SubCategory::destroy($id)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'record deleted'
                ]);
            }else{
                return response()->json([
                    'errors' => 'error occurred while deleting record'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'an exceptional error occurred'
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'errors' => 'an error occurred'
            ], 500);
        }
    }
}
