<?php

namespace Tests\Feature\Providers;

use App\Models\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateProviderTest extends TestCase {
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
            route('providers.store'), $this->data
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
    public function it_required_the_last_name()
    {
        $this->setData(['last_name' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('last_name');

        $this->setData(['last_name' => 1234])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('last_name');

    }

    /** @test */
    public function it_required_username()
    {
        $this->setData(['username' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('username');

        $this->setData(['username' => 12345])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('username');
    }

    /** @test */
    public function it_can_take_a_profiles_array()
    {
        $this->setData(['profiles' => null])
            ->store()
            ->assertJsonMissingValidationErrors('profiles');

        $this->setData(['profiles' => []])
            ->store()
            ->assertJsonMissingValidationErrors('profiles');

        $this->setData(['profiles' => 12345])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('profiles');
    }

    /** @test */
    public function it_required_image()
    {
        $this->setData(['image' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('image');
    }

    # </editor-fold>

    /** @test */
    public function an_authenticated_user_can_store_new_provider()
    {
        Storage::fake('media');
        $this->setData([
            'image' => jpeg_fake_base_64()
        ])->store()
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id', 'first_name', 'last_name', 'username', 'biography', 'image', 'profiles' => []
                ], 'message'
            ]);

        $this->assertDatabaseHas('providers', array_except($this->data, 'image'));
        Storage::disk('media')->assertExists(
            joinPath('providers', str_slug($this->data['username']) . '.jpeg')
        );
    }
}
