<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TeacherCreateQuizTest extends DuskTestCase
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
                ->click('#quiz-menu')
                ->assertSee('Select Course')
                ->click('#course-1')
                ->assertSee('Select Section')
                ->click('#section-1')
                ->assertSee('Quiz')
                ->clear('title')
                ->clear('description')
                ->type('title','title test quiz')
                ->type('description','description test quiz')
                ->press('#save-quiz')
                ->assertSee('title test quiz')
                ->click('#add-option')
                ->type('question','test question')
                ->attach('file','public/images/testing/logo.png')
                ->type('answer[0]','test true')
                ->type('point[0]',1)
                ->type('answer[1]','test false')
                ->type('point[1]',0)
                ->press('#sumbit-question')
                ->assertSee('Question Added')
                ->assertSee('test question')
            ;
        });
    }
}
