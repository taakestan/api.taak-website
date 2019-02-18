<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase {
    use RefreshDatabase, WithFaker;

    #-------------------------------------##   <editor-fold desc="The setUp">   ##----------------------------------------------------#

    /**
     * @var User $user
     */
    protected $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->user = create(User::class);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Guarded">   ##----------------------------------------------------#

    /**
     * checking guard data
     *
     * @param array $guardData
     */
    protected function assertGuard(array $guardData)
    {
        $this->user->update(
            raw(User::class, $guardData)
        );
        $this->assertDatabaseMissing('users', $guardData);
    }

    /** @test */
    public function it_should_guarded_the_id_field()
    {
        $this->assertGuard(['id' => 14048343]);
    }

    # </editor-fold>


    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /** @test */
    public function it_has_many_webinars()
    {
        $webinars = create('App\Models\Webinar', ['provider_id' => $this->user->id], 3);

        $webinars->each(function ($webinar) {
            $this->assertTrue($this->user->webinars->contains($webinar));
        });
    }

    # </editor-fold>
}
