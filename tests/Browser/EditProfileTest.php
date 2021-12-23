<?php

namespace Tests\Browser;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditProfileTest extends DuskTestCase
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
                    ->click('#navbarDropdown')
                    ->click('#profile-menu')
                    ->assertPathIs('/profile/account')
                    ->assertSee('Account')
                    ->assertValue('#email',$user->email)
                    ->clear('fullname')
                    ->clear('birth_date')
                    ->clear('birth_place')
                    ->clear('contact')
                    ->clear('address')
                    ->type('fullname','testing01')
                    ->type('birth_date','09/01/1999')
                    ->type('birth_place','Bandung')
                    ->type('contact','0431743129')
                    ->type('address','Sukabirus')
                    ->attach('photo','public/images/testing/profile-pict.png')
                    ->press('update')
                    ->assertPathIs('/profile/account')
                    ->assertSee('Account updated!')
                    ->assertValue('#fullname','Testing01')
                    ->assertValue('#birth_date','1999-01-09')
                    ->assertValue('#birth_place','Bandung')
                    ->assertValue('#contact','0431743129')
                    ->assertValue('#address','Sukabirus')
                ;
        });
    }
}
