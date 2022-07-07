<?php

namespace App\Http\Controllers;

use App\Category;
use App\Slider;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $catList = getCatList();
        $sliders = Slider::orderBy('id', 'desc')->get();
        return view('Shop.index', compact('catList','sliders'));
    }
}
