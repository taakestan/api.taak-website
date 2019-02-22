<?php

namespace Tests\Feature\Webinars;

use App\Models\Webinar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateWebinarTest extends TestCase {

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
     * @return CreateWebinarTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Webinar::class, $override);

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
    public function guest_can_not_create_new_webinars()
    {
        $this->setData()
            ->postJson(
                route('webinars.store'), []
            )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_title()
    {
        $this->setData(['title' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');

        $this->setData(['title' => 1234])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');

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

    /** @test */
    public function it_required_image()
    {
        $this->setData(['image' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('image');
    }
    /** @test */
    public function it_required_banner()
    {
        $this->setData(['banner' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('banner');
    }

    /** @test */
    public function it_can_take_a_links_array()
    {
        $this->setData(['links' => null])
            ->store()
            ->assertJsonMissingValidationErrors('links');

        $this->setData(['links' => []])
            ->store()
            ->assertJsonMissingValidationErrors('links');

        $this->setData(['links' => 12345])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('links');
    }

    # </editor-fold>

    /** @test */
    public function it_store_new_webinar_into_database()
    {
        Storage::fake('media');

        $this->setData([
            'image' => jpeg_fake_base_64(),
            'banner' => jpeg_fake_base_64(),
        ])->store()
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id', 'title', 'label', 'slug', 'description', 'content', 'links', 'image' , 'banner'
                ], 'message'
            ]);
        $this->assertDatabaseHas('webinars', array_except($this->data, ['image' , 'banner']));

        Storage::disk('media')->assertExists(Webinar::first()->image);
        Storage::disk('media')->assertExists(Webinar::first()->image);
    }
}
