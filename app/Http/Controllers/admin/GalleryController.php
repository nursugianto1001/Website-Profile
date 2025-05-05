<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posters = Gallery::getByType('poster');
        $documentations = Gallery::getByType('documentation');
        
        return view('admin.gallery.index', compact('posters', 'documentations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:poster,documentation',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer'
        ]);

        // Handle image upload
        $path = $request->file('image')->store('galleries', 'public');
        
        Gallery::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image_path' => $path,
            'type' => $validated['type'],
            'is_featured' => $request->has('is_featured'),
            'display_order' => $validated['display_order'] ?? 0
        ]);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery item added successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:poster,documentation',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer'
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'is_featured' => $request->has('is_featured'),
            'display_order' => $validated['display_order'] ?? 0
        ];

        // Handle image update if needed
        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image_path) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            
            // Store new image
            $data['image_path'] = $request->file('image')->store('galleries', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery item updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        // Delete the associated image
        if ($gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        
        // Delete the gallery item
        $gallery->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery item deleted successfully!');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Gallery $gallery)
    {
        $gallery->update([
            'is_featured' => !$gallery->is_featured
        ]);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Featured status updated!');
    }
}