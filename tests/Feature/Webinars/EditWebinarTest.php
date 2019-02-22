<?php

namespace Tests\Feature\Webinars;

use App\Models\Webinar;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditWebinarTest extends TestCase
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
     * @return EditWebinarTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Webinar::class, $override);

        return $this;
    }

    /**
     * send the request to store the webinar
     *
     * @param null $webinarId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function update($webinarId = null)
    {
        $webinarId = $webinarId ?: create(Webinar::class)->id;

        return $this->signIn()->putJson(
            route('webinars.update', $webinarId), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_edit_a_webinar()
    {
        $this->putJson(
            route('webinars.update', 1), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_title()
    {
        $this->setData(['title' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');

        $this->setData(['title' => 1234])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');

    }

    /** @test */
    public function it_required_the_label()
    {
        $this->setData(['label' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('label');

        $this->setData(['label' => 12345])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('label');
    }

    /** @test */
    public function it_required_description()
    {
        $this->setData(['description' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('description');

        $this->setData(['description' => 12345])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('description');
    }

    /** @test */
    public function it_required_content()
    {
        $this->setData(['content' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('content');

        $this->setData(['content' => 12345])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('content');
    }

    /** @test */
    public function it_required_provider_id()
    {
        $this->setData(['provider_id' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('provider_id');

        $this->setData(['provider_id' => 12345])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('provider_id');
    }

    /** @test */
    public function it_required_image()
    {
        $this->setData(['image' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('image');
    }
    /** @test */
    public function it_required_banner()
    {
        $this->setData(['banner' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('banner');
    }

    /** @test */
    public function it_can_take_a_links_array()
    {
        $this->setData(['links' => null])
            ->update()
            ->assertJsonMissingValidationErrors('links');

        $this->setData(['links' => []])
            ->update()
            ->assertJsonMissingValidationErrors('links');

        $this->setData(['links' => 12345])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('links');
    }


    # </editor-fold>

    /** @test */
    public function an_authenticated_user_can_update_a_webinar()
    {
        $webinar = create(Webinar::class);
        $this->setData()
            ->update($webinar->id)
            ->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('webinars', array_intersect($this->data, $webinar->toArray()));
    }

    /** @test */
    public function an_authenticated_user_can_update_a_webinar_with_image_updating()
    {
        Storage::fake('media');

        $webinar = create(Webinar::class);
        $this->setData([
            'image' => jpeg_fake_base_64(),
            'banner' => jpeg_fake_base_64()
        ])->update($webinar->id)
            ->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('webinars', array_except($this->data, ['image' , 'banner']));
        $this->assertDatabaseMissing('webinars', $webinar->toArray());

        Storage::disk('media')->assertExists(Webinar::first()->image);

        Storage::disk('media')->assertExists(Webinar::first()->banner);

    }
}
