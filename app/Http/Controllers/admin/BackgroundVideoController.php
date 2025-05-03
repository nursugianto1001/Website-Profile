<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackgroundVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BackgroundVideoController extends Controller
{
    public function index()
    {
        $videos = BackgroundVideo::all();
        return view('admin.background-videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.background-videos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video' => 'required|file|mimetypes:video/mp4,video/webm,video/ogg|max:20480',
        ]);

        DB::beginTransaction();
        try {
            // Upload file
            $path = $request->file('video')->store('background-videos', 'public');
            
            // Create record
            BackgroundVideo::create([
                'title' => $request->title,
                'path' => $path,
                'mime_type' => $request->file('video')->getMimeType(),
                'is_active' => false,
            ]);
            
            DB::commit();
            return redirect()->route('admin.background-videos.index')
                ->with('success', 'Video background berhasil diunggah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(BackgroundVideo $backgroundVideo)
    {
        return view('admin.background-videos.edit', compact('backgroundVideo'));
    }

    public function update(Request $request, BackgroundVideo $backgroundVideo)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video' => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg|max:20480',
        ]);

        DB::beginTransaction();
        try {
            // Jika ada file baru
            if ($request->hasFile('video')) {
                // Hapus file lama
                if ($backgroundVideo->path && Storage::disk('public')->exists($backgroundVideo->path)) {
                    Storage::disk('public')->delete($backgroundVideo->path);
                }
                
                // Upload file baru
                $path = $request->file('video')->store('background-videos', 'public');
                
                $backgroundVideo->path = $path;
                $backgroundVideo->mime_type = $request->file('video')->getMimeType();
            }
            
            $backgroundVideo->title = $request->title;
            $backgroundVideo->save();
            
            DB::commit();
            return redirect()->route('admin.background-videos.index')
                ->with('success', 'Video background berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(BackgroundVideo $backgroundVideo)
    {
        DB::beginTransaction();
        try {
            // Hapus file
            if ($backgroundVideo->path && Storage::disk('public')->exists($backgroundVideo->path)) {
                Storage::disk('public')->delete($backgroundVideo->path);
            }
            
            $backgroundVideo->delete();
            
            DB::commit();
            return redirect()->route('admin.background-videos.index')
                ->with('success', 'Video background berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function setActive(BackgroundVideo $backgroundVideo)
    {
        DB::beginTransaction();
        try {
            // Nonaktifkan semua video terlebih dahulu
            BackgroundVideo::where('is_active', true)->update(['is_active' => false]);
            
            // Aktifkan video yang dipilih
            $backgroundVideo->is_active = true;
            $backgroundVideo->save();
            
            DB::commit();
            return back()->with('success', 'Video background berhasil diaktifkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}