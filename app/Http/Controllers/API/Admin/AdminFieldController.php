<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminFieldController extends Controller
{
    /**
     * Display a listing of all fields
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = Field::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $fields
        ]);
    }

    /**
     * Store a newly created field
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_hour' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'opening_hour' => 'required|integer|min:0|max:23',
            'closing_hour' => 'required|integer|min:1|max:24|gt:opening_hour',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('fields', 'public');
            $data['image_url'] = Storage::url($path);
        }

        $field = Field::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Field created successfully',
            'data' => $field
        ], 201);
    }

    /**
     * Display the specified field
     *
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function show(Field $field)
    {
        return response()->json([
            'success' => true,
            'data' => $field
        ]);
    }

    /**
     * Update the specified field
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Field $field)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price_per_hour' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'opening_hour' => 'nullable|integer|min:0|max:23',
            'closing_hour' => 'nullable|integer|min:1|max:24|gt:opening_hour',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Remove old image if exists
            if ($field->image_url) {
                $oldPath = str_replace('/storage/', 'public/', $field->image_url);
                Storage::delete($oldPath);
            }

            $path = $request->file('image')->store('fields', 'public');
            $data['image_url'] = Storage::url($path);
        }

        $field->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Field updated successfully',
            'data' => $field
        ]);
    }

    /**
     * Remove the specified field
     *
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function destroy(Field $field)
    {
        // Check if there are bookings associated with this field
        if ($field->bookings()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete this field because it has bookings associated with it'
            ], 400);
        }

        // Remove image if exists
        if ($field->image_url) {
            $path = str_replace('/storage/', 'public/', $field->image_url);
            Storage::delete($path);
        }

        $field->delete();

        return response()->json([
            'success' => true,
            'message' => 'Field deleted successfully'
        ]);
    }

    /**
     * Toggle field status (active/inactive)
     *
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Field $field)
    {
        $field->update(['is_active' => !$field->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Field status updated successfully',
            'data' => $field
        ]);
    }
}
