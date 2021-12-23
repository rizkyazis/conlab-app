<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PointReviewCertificateTest extends DuskTestCase
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
                ->assertPathIs($user->role === 'student'?'/':'/dashboard')
                ->assertSeeIn('#navbarDropdown',$user->account->fullname?$user->account->fullname:$user->username)
                ->click('#navbarDropdown')
                ->click('#score-menu')
                ->assertPathIs('/score')
                ->assertSee('Your Point')
                ->assertSee('Score :')
                ->scrollTo('#get-certificate-1')
                ->click('#get-certificate-1')
                ->assertSee('Certificate printed!')
                ;
        });
    }
}
