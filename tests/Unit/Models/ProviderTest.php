<?php

namespace Tests\Unit\Models;

use App\Models\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProviderTest extends TestCase {

    use RefreshDatabase, WithFaker;

    #-------------------------------------##   <editor-fold desc="The setUp">   ##----------------------------------------------------#

    /**
     * @var Provider $provider
     */
    protected $provider;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->provider = create(Provider::class);
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
        $this->provider->update(
            raw(Provider::class, $guardData)
        );
        $this->assertDatabaseMissing('providers', $guardData);
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
        $webinars = create('App\Models\Webinar', ['provider_id' => $this->provider->id], 3);

        $webinars->each(function ($webinar) {
            $this->assertTrue($this->provider->webinars->contains($webinar));
        });
    }

    # </editor-fold>
}
