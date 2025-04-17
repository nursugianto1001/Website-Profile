<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Outlet;
use App\Models\Facility;
use App\Models\Career;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $featuredMenus = Menu::inRandomOrder()->limit(6)->get();
        $outlets = Outlet::inRandomOrder()->limit(3)->get();
        return view('public.home', compact('featuredMenus', 'outlets'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function menu()
    {
        $categories = Category::with('menus')->get();
        return view('public.menu', compact('categories'));
    }

    public function outlets()
    {
        $outlets = Outlet::all();
        return view('public.outlets', compact('outlets'));
    }

    public function facilities()
    {
        $facilities = Facility::all();
        return view('public.facilities', compact('facilities'));
    }

    public function careers()
    {
        $careers = Career::latest()->get();
        return view('public.careers', compact('careers'));
    }

    public function contact()
    {
        return view('public.contact');
    }
}
