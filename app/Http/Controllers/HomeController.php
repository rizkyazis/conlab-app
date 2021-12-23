<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $tags = Tag::all();

        return view('home')->with('tags', $tags);
    }
}
