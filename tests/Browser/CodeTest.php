<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CodeTest extends DuskTestCase
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
                ->visitRoute('lesson',[1,2,3])
                ->clickAtPoint($x = 331, $y = 839)
                ->driver->getKeyboard()->sendKeys('<h1>Hello World</h1>');
            $browser->press('submit')
                ->assertSee('Your code is submitted')
            ;
        });
    }
}
