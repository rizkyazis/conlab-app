<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;


class AuthenticationTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function access_register_page_success(){
        $respon = $this->get('/register');
        $respon->assertStatus(200);
    }

    /** @test */
    public function access_register_page_fail(){
        $user_find= User::where('role','student')->first();
        $password = 'conlab123';
        $user = [
            'email'=>$user_find->email,
            'password'=>$password
        ];

        $respon =$this->post('/login',$user);
        $respon = $this->get('/register');
        $respon->assertStatus(302);
    }

    /** @test */
    public function access_login_page_success(){
        $respon = $this->get('/login');
        $respon->assertStatus(200);
    }

    /** @test */
    public function access_login_page_fail(){
        $user_find= User::where('role','student')->first();
        $password = 'conlab123';
        $user = [
            'email'=>$user_find->email,
            'password'=>$password
        ];

        $respon =$this->post('/login',$user);
        $respon = $this->get('/login');
        $respon->assertStatus(302);
    }

    /** @test */
    public function register_success()
    {
        $username = $this->faker->unique()->userName;
        $email = $this->faker->unique()->safeEmail;
        $password = 'conlab123';
        $user = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'password_confirmation'=>$password
            ];

        $respon = $this->post( '/register',$user);
        $respon->assertSessionHasNoErrors();
        $respon->assertRedirect('/profile');

        array_splice($user,2);
        $this->assertDatabaseHas('users',$user);

    }

    /** @test */
    public function register_fail()
    {
        //username already exist
        $username = 'student';
        //email already exist
        $email = 'student@conlab.com';
        $password = 'conlab123';
        $user = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            //password and password confirmation not equals
            'password_confirmation'=>'conlab321'
        ];

        $respon = $this->post( '/register',$user);
        $respon->assertSessionHasErrors();
    }

    /** @test */
    public function login_success(){
        $user_find= User::where('role','student')->first();
        $password = 'conlab123';
        $user = [
            'email'=>$user_find->email,
            'password'=>$password
        ];

        $respon =$this->post('/login',$user);
        $respon->assertSessionHasNoErrors();
        $respon->assertRedirect('/');
    }

    /** @test */
    public function login_fail(){
        $user_find= User::where('role','student')->first();
        //password is wrong
        $password = 'conlab321';
        $user = [
            'email'=>$user_find->email,
            'password'=>$password
        ];
        $respon =$this->post('/login',$user);
        $respon->assertSessionHasErrors();
    }

    /** @test */
    public function logout(){
        $email = 'student@conlab.com';
        $password = 'conlab123';
        $user = [
            'email'=>$email,
            'password'=>$password
        ];

        $login =$this->post('/login',$user);
        $login->assertSessionHasNoErrors();

        $respon = $this->post('/logout');
        $respon->assertSessionHasNoErrors();
        $respon->assertRedirect('/');
    }
}
