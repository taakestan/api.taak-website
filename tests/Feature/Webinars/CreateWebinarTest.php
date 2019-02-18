<?php

namespace Tests\Feature\Webinars;

use App\Models\Webinar;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        return $this->postJson(
            route('webinars.store'), $this->data
        );
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

    # </editor-fold>

    /** @test */
    public function it_store_new_product_into_database()
    {
        $this->setData()
            ->store()
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id', 'title', 'label', 'slug'
                ], 'message'
            ]);

        $this->assertDatabaseHas('webinars', $this->data);
    }
}
