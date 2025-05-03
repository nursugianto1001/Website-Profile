<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

class PublicController extends Controller
{
    public function home()
    {
        $facilities = Facility::all(); // atau query lain sesuai kebutuhan
        return view('public.home', compact('facilities'));
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
}
