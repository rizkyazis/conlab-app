<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ChangePasswordTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $user = User::where('email','testing01@testemail.com')->first();
        $new_password = 'testing123';
        $old_password = 'testing321';
        $this->browse(function (Browser $browser) use ($old_password, $new_password, $user) {
            $browser->visitRoute('login')
                    ->assertSee('Log in')
                    ->type('email',$user->email)
                    ->type('password',$old_password)
                    ->press('login')
                    ->assertSeeIn('#navbarDropdown',$user->account->fullname?$user->account->fullname:$user->username)
                    ->click('#navbarDropdown')
                    ->click('#profile-menu')
                    ->assertPathIs('/profile/account')
                    ->click('#password-menu')
                    ->assertSee('Password')
                    ->type('old_password',$old_password)
                    ->type('password',$new_password)
                    ->type('password_confirmation',$new_password)
                    ->press('save')
                    ->assertSee('Password Changed')
              ;
        });
    }
}
