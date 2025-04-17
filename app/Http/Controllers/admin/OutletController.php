<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OutletController extends Controller
{
    public function index()
    {
        $outlets = Outlet::latest()->get();
        return view('admin.outlets.index', compact('outlets'));
    }

    public function create()
    {
        return view('admin.outlets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'opening_hours' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('outlets', 'public');

        Outlet::create([
            'name' => $request->name,
            'address' => $request->address,
            'opening_hours' => $request->opening_hours,
            'contact' => $request->contact,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin.outlets.index')
            ->with('success', 'Outlet created successfully.');
    }

    public function edit(Outlet $outlet)
    {
        return view('admin.outlets.edit', compact('outlet'));
    }

    public function update(Request $request, Outlet $outlet)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'opening_hours' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'opening_hours' => $request->opening_hours,
            'contact' => $request->contact,
        ];

        if ($request->hasFile('image')) {
            if ($outlet->image_path) {
                Storage::disk('public')->delete($outlet->image_path);
            }
            $data['image_path'] = $request->file('image')->store('outlets', 'public');
        }

        $outlet->update($data);

        return redirect()->route('admin.outlets.index')
            ->with('success', 'Outlet updated successfully.');
    }

    public function destroy(Outlet $outlet)
    {
        if ($outlet->image_path) {
            Storage::disk('public')->delete($outlet->image_path);
        }
        
        $outlet->delete();

        return redirect()->route('admin.outlets.index')
            ->with('success', 'Outlet deleted successfully.');
    }
}
