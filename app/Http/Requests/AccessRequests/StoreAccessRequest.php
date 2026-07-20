<?php

namespace App\Http\Requests\AccessRequests;

use App\DTO\CreateAccessRequestData;
use Illuminate\Foundation\Http\FormRequest;

class StoreAccessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function toDto(): CreateAccessRequestData
    {
        return new CreateAccessRequestData(
            message: $this->filled('message') ? $this->string('message')->toString() : null,
        );
    }
}
