<?php

namespace Tests\Unit\Models;

use App\Models\Webinar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WebinarTest extends TestCase {

    use RefreshDatabase, WithFaker;

    #-------------------------------------##   <editor-fold desc="The setUp">   ##----------------------------------------------------#

    /**
     * @var Webinar $webinar
     */
    protected $webinar;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->webinar = create(Webinar::class);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Booting">   ##----------------------------------------------------#

    /** @test */
    public function it_attach_slug_to_model_object_when_creating_webinar()
    {
        $this->assertArrayHasKey(
            'slug', $this->webinar->toArray()
        );
    }

    /** @test */
    public function it_update_slug_to_new_en_title_when_updating_en_title()
    {
        $this->webinar->update([
            'en_title' => $en_title = 'the new english title'
        ]);

        $this->assertEquals(
            $this->webinar->en_title, $en_title
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /** @test */
    public function it_should_create_slug_from_the_en_title_of_webinar()
    {
        $webinar = create(Webinar::class, [
            'en_title' => $en_title = $this->faker->sentence

        ]);

        $this->assertEquals(
            $webinar->slug, str_slug($en_title)
        );
    }

    # </editor-fold>

}
