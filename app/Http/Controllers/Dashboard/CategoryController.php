<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(){
        $categories = Tag::all();
        return view('dashboard.category.index')
            ->with('categories',$categories);
    }

    public function category_search(Request $request){
        $categories = Tag::where('name', 'like', '%' . $request->query('name') . '%');

        if ($categories->count() === 0) {
            toast('Category not found', 'error');
            return redirect()->back();
        }

        return view('dashboard.category.index')
            ->with('categories',$categories);
    }

    public function create(Request $request){
        $rules = [
            'name'=>'required|max:150',
            'img'=>'required|max:5120|mimes:jpg,png,jpeg'
        ];


        $this->validate($request, $rules);
        $storage = Storage::put('public/category',$request->file('img'));

        $category = Tag::create([
            'name'=>$request->name,
            'image'=>$storage
        ]);

        toast('Success added new category','success');
        return redirect()->back();
    }
}
