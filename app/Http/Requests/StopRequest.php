<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StopRequest extends FormRequest
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
    public function rules()
    {
        return [
            'travel_id' => 'required|exists:travels,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'day' => 'required|integer|min:1|max:255',
            'food' => 'nullable|string',
            'place' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'rating' => 'nullable|integer|min:1|max:5',
        ];
    }


    public function messages()
    {
        return [
            'travel_id.required' => 'La tappa non è assegnata a nessun viaggio.',
            'travel_id.exists' => 'La tappa non è assegnata a nessun viaggio salvato.',
            'title.required' => 'Il campo titolo è obbligatorio.',
            'title.max' => 'Il titolo non può essere più lungo di :max caratteri.',
            'day.required' => 'Il campo giorno è obbligatorio.',
            'latitude.required' => 'Impossibile stabilire la latitudine del luogo selezionato.',
            'place.required' => 'Il campo luogo è obbligatorio',
            'longitude.required' => 'Impossibile stabilire la longitudine del luogo selezionato.',
            'rating.integer' => 'Il campo valutazione deve essere un numero intero tra 1 e 5.',
        ];
    }
}
