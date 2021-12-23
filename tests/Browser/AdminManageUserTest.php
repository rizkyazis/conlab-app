<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminManageUserTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $user = User::where('email','admin@conlab.com')->where('role','admin')->first();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visitRoute('login')
                ->assertSee('Log in')
                ->type('email',$user->email)
                ->type('password','conlab123')
                ->press('login')
                ->assertSeeIn('#navbarDropdown',$user->account->fullname?$user->account->fullname:$user->username)
                ->assertPathIs('/dashboard')
                ->click('#user-menu')
                ->assertSee('List User')
                ->select('#user-5','reviewer')
                ->assertSee('Roles changed')
                ->assertValue('#user-5','reviewer')
            ;
        });
    }
}
