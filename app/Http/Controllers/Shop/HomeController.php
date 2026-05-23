<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the home page displaying categories and featured products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all categories to display in the category cards section
        $categories = Category::all();

        // Get featured products limit 6
        $featuredProducts = Product::where('is_featured', true)
            ->with('category')
            ->take(6)
            ->get();

        return view('shop.home', compact('categories', 'featuredProducts'));
    }
}
