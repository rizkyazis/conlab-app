<?php

namespace Tests\Unit\Admin;

use FontLib\Table\Type\name;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CategoryManageTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function access_dashboard_category_success()
    {
        $email = 'admin@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $dashboard = $this->get('/dashboard/category');
        $dashboard->assertStatus(200);
        $dashboard->assertViewHas('categories');
    }

    /** @test */
    public function access_dashboard_category_fail()
    {
        $email = ['student@conlab.com', 'teacher@conlab.com', 'reviewer@conlab.com'];
        $password = 'conlab123';

        for ($i = 0; $i < 3; $i++) {
            $user = [
                'email' => $email[$i],
                'password' => $password
            ];

            $login = $this->post('/login', $user);
            $login->assertSessionHasNoErrors();

            $dashboard = $this->get('/dashboard/category');
            $dashboard->assertStatus(302);
        }
    }

    /** @test */
    public function create_category_success(){
        $email = 'admin@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        Storage::fake('logo');
        $title = $this->faker->streetName;

        $file = UploadedFile::fake()->image('logo.jpg');
        $category = [
            'name'=>$title,
            'img'=>$file
        ];
        $respon = $this->post('/dashboard/category/create',$category);
        $respon->assertSessionHasNoErrors();

        $this->assertFileExists('public/storage/category/'.$file->hashName());
        $this->assertDatabaseHas('tag',['name'=>$title,'image'=>'public/category/'.$file->hashName()]);
    }

    /** @test */
    public function create_category_fail(){
        $email = 'admin@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        Storage::fake('logo');
        $title = $this->faker->streetName;

        $file = UploadedFile::fake()->image('logo.mp4');
        $category = [
            'name'=>$title,
            'img'=>$file
        ];
        $respon = $this->post('/dashboard/category/create',$category);
        $respon->assertSessionHasErrors();

    }
}
