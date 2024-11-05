<?php

namespace Database\Factories;

use App\Models\Check;
use App\Models\Credential;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

final class CheckFactory extends Factory
{
    /**
     * @var class-string<Check>
     */
    protected $model = Check::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'path' => $this->faker->filePath(),
            'method' => 'GET',
            'body' => null,
            'headers' => null,
            'parameters' => null,
            'credential_id' => $this->faker->boolean
                ? Credential::factory()
                : null,
            'service_id' => Service::factory(),
        ];
    }
}
