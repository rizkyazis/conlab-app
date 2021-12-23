<?php

namespace Tests\Unit\Admin;

use App\User;
use Tests\TestCase;

class UserManageTest extends TestCase
{
    /** @test */
    public function access_dashboard_user_success()
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

        $dashboard = $this->get('/dashboard/user');
        $dashboard->assertStatus(200);
        $dashboard->assertViewHas('users');
    }

    /** @test */
    public function access_dashboard_user_fail()
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

            $dashboard = $this->get('/dashboard/user');
            $dashboard->assertStatus(302);
        }
    }

    /** @test */
    public function change_user_role_success()
    {
        $email = 'admin@conlab.com';
        $password = 'conlab123';

        $admin = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $admin);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        $userFind = User::where('email','testing@conlab.com')->first();

        $user = [
            'id' => $userFind->id,
            'role' => 'teacher'
        ];

        $respon = $this->put("/dashboard/user/$userFind->id/roles", ['role' => 'teacher']);
        $respon->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', $user);
    }

    /** @test */
    public function change_user_role_failed()
    {
        $email = 'admin@conlab.com';
        $password = 'conlab123';

        $admin = [
            'email' => $email,
            'password' => $password
        ];

        $login = $this->post('/login', $admin);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/dashboard');

        //user tidak ada di db
        $respon = $this->put('/dashboard/user/100/roles', ['role' => 'teacher']);
        $respon->assertSessionHas('error');

    }
}
