<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Gallery;

class PublicController extends Controller
{
    public function home()
    {
        $facilities = Facility::all(); // or other query as needed
        $featuredPosters = Gallery::getFeatured('poster', 3); // Get 3 featured posters
        $featuredDocumentations = Gallery::getFeatured('documentation', 6); // Get 6 featured documentation photos
        
        return view('public.home', compact('facilities', 'featuredPosters', 'featuredDocumentations'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function facilities()
    {
        $facilities = Facility::all();
        return view('public.facilities', compact('facilities'));
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function gallery()
    {
        $posters = Gallery::getByType('poster');
        $documentations = Gallery::getByType('documentation');
        return view('public.gallery', compact('posters', 'documentations'));
    }
}