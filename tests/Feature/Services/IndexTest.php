<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Services\IndexController;
use App\Models\Service;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

use Symfony\Component\HttpFoundation\Response;

test('an unauthenticated user gets the correct status code.', function (): void {
    getJson(
        uri: action(IndexController::class)
    )->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
});
test('an authenticated user gets the correct status code.', function (): void {
    actingAs(User::factory()->create())->getJson(
        uri: action(IndexController::class)
    )->assertStatus(Response::HTTP_OK);
});
test('an authenticated user can only see their own services.', function (): void {
    $user = User::factory()->create();
    Service::factory()->for($user)->count(2)->create();
    Service::factory()->count(10)->create();

    expect(
        Service::query()->count()
    )->toEqual(12);

    actingAs($user)->getJson(
        uri: action(IndexController::class)
    )->assertStatus(Response::HTTP_OK)->assertJson(
        fn (AssertableJson $json) => $json
            ->count(2)
            ->etc()
    );

});

test('the response comes in a standard format.', function (): void {
    $user = User::factory()->create();
    Service::factory()->for($user)->count(2)->create();
    actingAs($user)->getJson(
        uri: action(IndexController::class)
    )->assertStatus(Response::HTTP_OK)->assertJson(
        fn (AssertableJson $json) => $json
            ->each(
                fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('type')
                    ->where('type', 'services')
                    ->has('attributes')
                    ->has('links')
                    ->etc()
            )
    );
});
todo('the structure of the response meets the api design expectations.');
todo('the response is paginated.');
todo('a user can include additional relationships.');
todo('a user can filter their request to get specific data.');
todo('a user can sort their results to the order they require.');
