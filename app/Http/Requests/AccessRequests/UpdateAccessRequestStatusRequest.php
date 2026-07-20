<?php

namespace App\Http\Requests\AccessRequests;

use App\Enums\AccessRequestStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccessRequestStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('accessRequest')) ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(AccessRequestStatus::class)],
        ];
    }

    public function status(): AccessRequestStatus
    {
        return AccessRequestStatus::from($this->string('status')->toString());
    }
}
