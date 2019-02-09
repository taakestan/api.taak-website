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

    }

    /** @test */
    public function it_required_the_en_title()
    {
        $this->setData(['en_title' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('en_title');

    }

    # </editor-fold>

    /** @test */
    public function it_store_new_product_into_database()
    {
        $this->setData()
            ->store()
            ->assertStatus(200)
            ->assertJsonStructure(
                wrap_with_data(['webinar', 'message'])
            );

        $this->assertDatabaseHas('webinars', $this->data);
    }
}
