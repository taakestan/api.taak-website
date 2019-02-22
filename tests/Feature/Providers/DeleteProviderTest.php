<?php

namespace Tests\Feature\Providers;

use App\Models\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteProviderTest extends TestCase {
    use RefreshDatabase;

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * send the request to destroy the provider
     *
     * @param null $providerId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function destroy($providerId = null)
    {
        $providerId = $providerId ?: create(Provider::class);

        return $this->signIn()->deleteJson(
            route('providers.destroy', $providerId)
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_delete_a_provider()
    {
        $this->deleteJson(
            route('providers.destroy', 1)
        )->assertStatus(401);
    }

    # </editor-fold>

    /** @test */
    public function an_authenticated_admin_can_delete_a_provider()
    {
        $provider = create(Provider::class);

        $this->destroy($provider->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('providers', $provider->toArray());
    }

    /** @test */
    public function an_authenticated_admin_can_not_delete_a_provider_that_has_webinar()
    {
        $provider = create(Provider::class);
        $webinar = create('App\Models\Webinar' , ['provider_id' => $provider->id]);

        $this->destroy($provider->id)
            ->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('providers', $provider->toArray());
        $this->assertDatabaseHas('webinars', $webinar->toArray());
    }
}
