<?php

namespace Tests\Feature\Webinars;

use App\Models\Webinar;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteWebinarTest extends TestCase
{
    use RefreshDatabase;

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * send the request to destroy the webinar
     *
     * @param null $webinarId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function destroy($webinarId = null)
    {
        $webinarId = $webinarId ?: create(Webinar::class);

        return $this->signIn()->deleteJson(
            route('webinars.destroy', $webinarId)
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_delete_a_webinar()
    {
        $this->deleteJson(
            route('webinars.destroy', 1)
        )->assertStatus(401);
    }

    # </editor-fold>

    /** @test */
    public function an_authenticated_admin_can_delete_a_webinar()
    {
        $webinar = create(Webinar::class);

        $this->destroy($webinar->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('webinars', $webinar->toArray());
    }

}
