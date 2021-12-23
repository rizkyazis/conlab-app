<?php

namespace Tests\Unit;

use Tests\TestCase;

class RoleTest extends TestCase
{
    /** @test */
    public function user_access_dashboard_success()
    {
        $email = ['admin@conlab.com','teacher@conlab.com','reviewer@conlab.com'];
        $password = 'conlab123';

        for($i = 0;$i<3;$i++){
            $user = [
                'email'=>$email[$i],
                'password'=>$password
            ];

            $login =$this->post('/login',$user);
            $login->assertSessionHasNoErrors();
            $login->assertRedirect('/dashboard');

            $respon = $this->post('/logout');
            $respon->assertSessionHasNoErrors();
            $respon->assertRedirect('/');
        }

    }

    /** @test */
    public function user_access_dashboard_fail()
    {
        $email = 'student@conlab.com';
        $password = 'conlab123';

        $user = [
            'email'=>$email,
            'password'=>$password
        ];

        $login =$this->post('/login',$user);
        $login->assertSessionHasNoErrors();
        $login->assertRedirect('/');

        $dashboard = $this->get('/dashboard');
        $dashboard->assertRedirect('/');
    }

}
