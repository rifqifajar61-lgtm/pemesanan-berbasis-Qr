<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $menus = Menu::where('is_available', true)->get();
        
        return view('customer.index', compact('categories', 'menus'));
    }
}