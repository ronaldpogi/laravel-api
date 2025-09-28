<?php

namespace App\Http\Requests\Saas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $routeParam = $this->route('id');

        $tenantId = is_object($routeParam) ? $routeParam->id : $routeParam;

        return [
            // FOR USER TABLE
            'email'    => ['required', 'email', 'max:255'],
            'phone'    => ['required', 'int'],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:8', 'confirmed'],

            'name'      => ['required', 'string', 'max:255'],
            'address'   => ['required', 'string', 'max:500'],
            'subdomain' => [
                'required',
                'string',
                'max:255',
                Rule::unique('saas_tenants', 'subdomain')->ignore($tenantId),
            ],
            'settings' => ['nullable', 'array'], // JSON stored as array
        ];
    }
}
