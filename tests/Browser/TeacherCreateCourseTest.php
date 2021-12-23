<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TeacherCreateCourseTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $user = User::where('email','teacher@conlab.com')->where('role','teacher')->first();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visitRoute('login')
                ->assertSee('Log in')
                ->type('email',$user->email)
                ->type('password','conlab123')
                ->press('login')
                ->assertSeeIn('#navbarDropdown',$user->account->fullname?$user->account->fullname:$user->username)
                ->assertPathIs('/dashboard')
                ->click('#course-menu')
                ->assertSee('List Course')
                ->click('#add-course')
                ->assertSee('Create New Course')
                ->type('title','Testing Course')
                ->type('description','This is a Course used for Testing only')
                ->select('difficulty','beginner')
                ->attach('img','public/images/testing/logo.png')
                ->select('category','1')
                ->type('#editor','hei')
                ->press('save-course')
                ;
        });
    }
}
