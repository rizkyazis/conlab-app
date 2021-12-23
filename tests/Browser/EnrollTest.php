<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EnrollTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $user = User::where('email','testing01@testemail.com')->first();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visitRoute('login')
                ->assertSee('Log in')
                ->type('email',$user->email)
                ->type('password','testing123')
                ->press('login')
                ->assertSeeIn('#navbarDropdown',$user->account->fullname?$user->account->fullname:$user->username)
                ->visitRoute('course',1)
                ->click('#enroll-course')
                ->assertSee('Enroll success!')
            ;
        });
    }
}
