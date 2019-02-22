<?php

namespace Tests\Feature\Providers;

use App\Models\Provider;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetProviderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_see_provider_in_route_providers_index()
    {
        $provider = create(Provider::class);

        $this->getJson(route('providers.index'))
            ->assertStatus(200)
            ->assertSee($provider->title);
    }

    /** @test */
    public function it_see_provider_in_route_providers_index_in_this_format()
    {
        create(Provider::class);

        return $this->getJson(route('providers.index'))
            ->assertJsonStructure([
                    'data' => [
                        ['id', 'first_name', 'last_name', 'username', 'biography' , 'profiles' => []]
                    ]
                ]
            );
    }

    /** @test */
    public function it_see_all_provider_has_been_created_exactly()
    {
        $providers = create(Provider::class, [], 2);

        return $this->getJson(route('providers.index'))
            ->assertExactJson(
                [
                    'data' => \App\Http\Resources\ProviderResource::collection($providers)->resolve()
                ]
            );
    }
    
    /** @test */
    public function it_show_one_provider()
    {
        $provider = create(Provider::class);

        $this->getJson(route('providers.show' , $provider->id))
            ->assertStatus(200)
            ->assertSee($provider->title);
    }

    /** @test */
    public function it_see_provider_in_route_providers_show_in_this_format()
    {
        $provider = create(Provider::class);

        return $this->getJson(route('providers.show' , $provider->id))
            ->assertJsonStructure([
                    'data' => [
                        'id', 'first_name', 'last_name', 'username', 'biography' , 'profiles' => []
                    ]
                ]
            );
    }
}
