<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TravelRequest extends FormRequest
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
        return [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|min:3|max:150',
            'starting_date' => 'required|date',
            'ending_date' => 'required|date|after_or_equal:starting_date',
            'country' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Il viaggio deve appartenere ad un utente',
            'user_id.exists' => 'L\'utente a cui si cerca di attribuire il viaggio non esiste',

            'title.required' => 'Il titolo è obbligatorio.',
            'title.max' => 'Il titolo non può essere più lungo di 150 caratteri.',

            'starting_date.required' => 'La data di inizio è obbligatoria.',
            'starting_date.date' => 'La data di inizio deve essere una data valida.',

            'ending_date.required' => 'La data di fine è obbligatoria.',
            'ending_date.date' => 'La data di fine deve essere una data valida.',
            'ending_date.after_or_equal' => 'La data di fine deve essere uguale o successiva alla data di inizio.',
        ];
    }
}
