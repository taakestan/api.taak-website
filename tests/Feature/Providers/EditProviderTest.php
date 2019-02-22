<?php

namespace Tests\Feature\Providers;

use App\Models\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditProviderTest extends TestCase {
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
     * @return EditProviderTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Provider::class, $override);

        return $this;
    }

    /**
     * send the request to store the webinar
     *
     * @param null $providerId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function update($providerId = null)
    {
        $providerId = $providerId ?: create(Provider::class)->id;

        return $this->signIn()->putJson(
            route('providers.update', $providerId), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_edit_a_provider()
    {
        $this->putJson(
            route('providers.update', 1), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_first_name()
    {
        $this->setData(['first_name' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('first_name');

        $this->setData(['first_name' => 1234])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('first_name');

    }

    /** @test */
    public function it_required_the_last_name()
    {
        $this->setData(['last_name' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('last_name');

        $this->setData(['last_name' => 1234])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('last_name');

    }

    /** @test */
    public function it_required_username()
    {
        $this->setData(['username' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('username');

        $this->setData(['username' => 12345])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('username');
    }

    /** @test */
    public function it_can_take_a_profiles_array()
    {
        $this->setData(['profiles' => null])
            ->update()
            ->assertStatus(200);

        $this->setData(['profiles' => []])
            ->update()
            ->assertStatus(200);

        $this->setData(['profiles' => 12345])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('profiles');
    }

    # </editor-fold>

    /** @test */
    public function an_authenticated_user_can_update_a_provider()
    {
        $provider = create(Provider::class);
        $this->setData()
            ->update($provider->id)
            ->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('providers', array_except( $this->data, 'image'));
    }

    /** @test */
    public function an_authenticated_user_can_update_a_provider_with_image_updating()
    {
        Storage::fake('media');

        $provider = create(Provider::class);
        $this->setData([
            'image' => jpeg_fake_base_64()
        ])->update($provider->id)
            ->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('providers', array_except($this->data, 'image'));

        Storage::disk('media')->assertExists(
            joinPath('providers', str_slug($this->data['username']) . '.jpeg')
        );

    }
}
