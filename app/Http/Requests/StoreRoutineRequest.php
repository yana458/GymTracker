<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoutineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Validation rules for routine creation
            'name' => ['required','string'],
            'description' => ['nullable','string'],

            'exercises' => ['required','array','min:1'],
            'exercises.*.id' => ['required','integer','exists:exercises,id'],
            
            'exercises.*.sequence' => ['required','integer','min:1'],
            'exercises.*.target_sets' => ['required','integer','min:1'],
            'exercises.*.target_reps' => ['required','integer','min:1'],
            'exercises.*.rest_seconds' => ['required','integer','min:0'],
        ];
    }
}
