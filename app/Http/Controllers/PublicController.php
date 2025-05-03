<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home');
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