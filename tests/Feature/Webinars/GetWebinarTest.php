<?php

namespace Tests\Feature\Webinars;

use App\Models\Webinar;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetWebinarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_see_webinar_in_route_webinars_index()
    {
        $webinar = create(Webinar::class);

        $this->getJson(route('webinars.index'))
            ->assertStatus(200)
            ->assertSee($webinar->title);
    }

    /** @test */
    public function it_see_webinar_in_route_webinars_index_in_this_format()
    {
        create(Webinar::class);

        return $this->getJson(route('webinars.index'))
            ->assertJsonStructure([
                    'data' => [
                        ['id', 'title', 'label', 'slug', 'provider' , 'links' => [], 'image', 'banner']
                    ]
                ]
            );
    }


    /** @test */
    public function it_show_one_webinar()
    {
        $webinar = create(Webinar::class);

        $this->getJson(route('webinars.show' , $webinar->id))
            ->assertStatus(200)
            ->assertSee($webinar->title);
    }

    /** @test */
    public function it_see_webinar_in_route_webinars_show_in_this_format()
    {
        $webinar = create(Webinar::class);

        return $this->getJson(route('webinars.show' , $webinar->id))
            ->assertJsonStructure([
                    'data' => [
                        'id', 'title', 'label', 'slug', 'provider' , 'links' => [], 'image', 'banner'
                    ]
                ]
            );
    }
}
