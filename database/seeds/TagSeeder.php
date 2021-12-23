<?php

use App\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = ['HTML','Python'];
        $image = ['public/images/seed/html.png','public/images/seed/python.png'];
        $copied_image = ['public/storage/category/html.png','public/storage/category/python.png'];
        $image_url = ['public/category/html.png','public/category/python.png'];

        if(!Storage::disk('public')->exists('category')){
            Storage::disk('public')->makeDirectory('category');
        }

        for($i = 0;$i<2;$i++){
            copy($image[$i],$copied_image[$i]);
            Tag::create([
               'name'=>$name[$i],
               'image'=>$image_url[$i]
            ]);
        }

    }
}
