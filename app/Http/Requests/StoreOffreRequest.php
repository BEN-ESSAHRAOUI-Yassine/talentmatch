<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOffreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'required_skills' => ['required', 'array', 'min:1'],
            'required_skills.*' => ['string'],
            'minimum_experience' => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required_skills.required' => 'Au moins une compétence est requise.',
            'required_skills.min' => 'Au moins une compétence est requise.',
            'minimum_experience.min' => 'L\'expérience minimale ne peut pas être négative.',
        ];
    }
}
