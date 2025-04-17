<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        $careers = Career::latest()->get();
        return view('admin.careers.index', compact('careers'));
    }

    public function create()
    {
        return view('admin.careers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
        ]);

        Career::create($request->all());

        return redirect()->route('admin.careers.index')
            ->with('success', 'Career opportunity created successfully.');
    }

    public function edit(Career $career)
    {
        return view('admin.careers.edit', compact('career'));
    }

    public function update(Request $request, Career $career)
    {
        $request->validate([
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
        ]);

        $career->update($request->all());

        return redirect()->route('admin.careers.index')
            ->with('success', 'Career opportunity updated successfully.');
    }

    public function destroy(Career $career)
    {
        $career->delete();

        return redirect()->route('admin.careers.index')
            ->with('success', 'Career opportunity deleted successfully.');
    }
}
