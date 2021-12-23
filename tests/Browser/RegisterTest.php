<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     * @group auth
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('register')
                    ->assertSee('Create Account')
                    ->type('username','testing01')
                    ->type('email','testing01@testemail.com')
                    ->type('password','testing123')
                    ->type('password_confirmation','testing123')
                    ->press('create')
                    ->assertPathIs('/profile/account')
                    ->assertSeeIn('#navbarDropdown','testing01')
            ;
        });
    }
}
