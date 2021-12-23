<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminCreateCategoryTest extends DuskTestCase
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
                ->click('#category-menu')
                ->assertSee('List Category')
                ->press('#modal-create')
                ->waitForText('Create new Category','2')
                ->type('#name-category','Conlab Testing')
                ->attach('img','public/images/testing/logo.png')
                ->press('#save-category')
                ->assertSee('Success added new category')
                ->assertSee('Conlab Testing')
            ;
        });
    }
}
