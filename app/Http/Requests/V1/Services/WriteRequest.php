<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Services;

use App\Http\Payloads\V1\CreateService;
use Illuminate\Foundation\Http\FormRequest;

final class WriteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'url' => ['required', 'url', 'min:11', 'max:255'],
        ];
    }

    public function payload(): CreateService
    {
        return new CreateService(
            name: $this->string('name')->toString(),
            url: $this->string('url')->toString(),
            user: $this->user()->id,
        );
    }
}
