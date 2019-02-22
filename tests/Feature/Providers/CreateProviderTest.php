<?php

namespace Tests\Feature\Providers;

use App\Models\Provider;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateProviderTest extends TestCase
{
    use RefreshDatabase;

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return CreateProviderTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Provider::class, $override);

        return $this;
    }

    /**
     * send the request to store the webinar
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function store()
    {
        return $this->signIn()->postJson(
            route('webinars.store'), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_create_new_provider()
    {
        $this->setData()
            ->postJson(
                route('providers.store'), []
            )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_first_name()
    {
        $this->setData(['first_name' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('first_name');

        $this->setData(['first_name' => 1234])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('first_name');

    }

    /** @test */
    public function it_required_the_label()
    {
        $this->setData(['label' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('label');

        $this->setData(['label' => 12345])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('label');
    }

    /** @test */
    public function it_required_description()
    {
        $this->setData(['description' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('description');

        $this->setData(['description' => 12345])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('description');
    }

    /** @test */
    public function it_required_content()
    {
        $this->setData(['content' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('content');

        $this->setData(['content' => 12345])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('content');
    }

    /** @test */
    public function it_required_provider_id()
    {
        $this->setData(['provider_id' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('provider_id');

        $this->setData(['provider_id' => 12345])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('provider_id');
    }

    # </editor-fold>

    /** @test */
    public function it_store_new_webinar_into_database()
    {
        $this->setData()
            ->store()
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id', 'title', 'label', 'slug', 'description', 'content'
                ], 'message'
            ]);

        $this->assertDatabaseHas('webinars', $this->data);
    }
}
