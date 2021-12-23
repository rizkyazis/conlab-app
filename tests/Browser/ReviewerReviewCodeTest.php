<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ReviewerReviewCodeTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $user = User::where('email','reviewer@conlab.com')->where('role','reviewer')->first();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visitRoute('login')
                ->assertSee('Log in')
                ->type('email',$user->email)
                ->type('password','conlab123')
                ->press('login')
                ->assertSeeIn('#navbarDropdown',$user->account->fullname?$user->account->fullname:$user->username)
                ->assertPathIs('/dashboard')
                ->click('#review-menu')
                ->assertSee('Select Course to Review')
                ->click('#course-1')
                ->assertSee('Select Lesson to review')
                ->click('#lesson-1')
                ->assertSee('Select Participants to review')
                ->click('#participant-1')
                ->assertSee('Review Code')
                ->clear('feedback')
                ->clear('score')
                ->type('feedback','test review')
                ->type('score',80)
                ->press('#submit-review')
                ->assertSee('Code has ben reviewed!')
                ->assertValue('#feedback','test review')
                ->assertValue('#score',80)
                ;
        });
    }
}
