<?php

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ProfileTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function access_profile_account_success()
    {
        $email = 'testing@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get('/profile');
        $dashboard->assertStatus(200);
    }

    /** @test */
    public function access_profile_account_fail()
    {
        $dashboard = $this->get('/profile');
        $dashboard->assertStatus(302);
    }

    /** @test */
    public function change_profile_account_success()
    {
        $email = 'testing@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $file = UploadedFile::fake()->image('photo.jpg');

        $fullname = $this->faker->name;
        $birth_date = $this->faker->date('y-m-d');
        $birth_place = $this->faker->city;
        $contact = $this->faker->phoneNumber;
        $address = $this->faker->address;

        $account = [
            'fullname'=>$fullname,
            'birth_date'=>$birth_date,
            'birth_place'=>$birth_place,
            'contact'=>$contact,
            'address'=>$address,
            'photo'=>$file
        ];

        $respon = $this->post('/profile',$account);
        $respon->assertSessionHasNoErrors();

        $this->assertFileExists('public/storage/profile/'.$file->hashName());
        $this->assertDatabaseHas('accounts',[
            'fullname'=>$fullname,
            'birth_date'=>$birth_date,
            'birth_place'=>$birth_place,
            'contact'=>$contact,
            'address'=>$address,
            'photo'=>'public/profile/'.$file->hashName()
        ]);
    }

    /** @test */
    public function change_profile_account_fail()
    {
        $email = 'testing@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        //format file salah (seharusnya format gambar)
        $file = UploadedFile::fake()->image('photo.mp4');

        $fullname = $this->faker->name;
        $birth_date = $this->faker->date('y-m-d');
        $birth_place = $this->faker->city;
        $contact = $this->faker->phoneNumber;
        $address = $this->faker->address;

        $account = [
            'fullname'=>$fullname,
            'birth_date'=>$birth_date,
            'birth_place'=>$birth_place,
            'contact'=>$contact,
            'address'=>$address,
            'photo'=>$file
        ];

        $respon = $this->post('/profile');
        $respon->assertSessionHasErrors();
    }

    /** @test */
    public function access_profile_password_success()
    {
        $email = 'testing@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $dashboard = $this->get('/profile');
        $dashboard->assertStatus(200);
    }

    /** @test */
    public function access_profile_password_fail()
    {
        $dashboard = $this->get('/profile');
        $dashboard->assertStatus(302);
    }

    /** @test */
    public function change_profile_password_success()
    {
        $email = 'testing@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        $account = [
            'old_password'=>'conlab123',
            'password'=>'conlab321',
            'password_confirmation'=>'conlab321'
        ];

        $respon = $this->post('/profile/password',$account);
        $respon->assertSessionHasNoErrors();

        //ganti password menjadi password sebelumnya
        $account = [
            'old_password'=>'conlab321',
            'password'=>'conlab123',
            'password_confirmation'=>'conlab123'
        ];

        $respon = $this->post('/profile/password',$account);
        $respon->assertSessionHasNoErrors();

    }

    /** @test */
    public function change_profile_password_fail()
    {
        $email = 'testing@conlab.com';
        $password = 'conlab123';

        $user = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $user);
        $login->assertSessionHasNoErrors();

        //password new dan password confirmation tidak sama
        $account = [
            'old_password'=>'conlab123',
            'password'=>'conlab321',
            'password_confirmation'=>'conlab123'
        ];

        $respon = $this->post('/profile/password',$account);
        $respon->assertSessionHasErrors();

    }
}
