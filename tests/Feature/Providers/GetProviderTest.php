<?php

namespace Tests\Feature\Providers;

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
            ->assertJsonStructure(
                $this->wrapWithData([
                    ['id', 'title', 'label', 'slug', 'provider_group_id']
                ])
            );
    }

    /** @test */
    public function it_see_all_provider_has_been_created_exactly()
    {
        $providers = create(Provider::class, [], 2);

        return $this->getJson(route('providers.index'))
            ->assertExactJson(
                $this->wrapWithData(
                    \App\Http\Resources\ProviderResource::collection($providers)->resolve()
                )
            );
    }

    /** @test */
    public function it_filter_by_provider_group_id()
    {
        $providerGroup = create('App\Models\ProviderGroup');

        $provider1 = create(Provider::class, ['provider_group_id' => $providerGroup->id]);
        $provider2 = create(Provider::class);

        return $this->getJson(route('providers.index', ['provider_group_id' => $providerGroup->id]))
            ->assertStatus(200)
            ->assertSee($provider1->title)
            ->assertDontSee($provider2->title);
    }
}
