<?php

namespace App\Http\Requests\Saas;

use Illuminate\Foundation\Http\FormRequest;

class AttachPermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permission_ids'   => ['required', 'array', 'min:1'],
            'permission_ids.*' => ['integer', 'exists:saas_permissions,id'],
        ];
    }
}
