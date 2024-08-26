<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
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
            'travel_id' => 'required|exists:travels,id',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'travel_id.required' => 'L\'immagine non è assegnata a nessun viaggio.',
            'travel_id.exists' => 'L\'immagine non è assegnata a nessun viaggio salvato.',

            'images.*.required' => 'Nessuna immagine da salvare',
            'images.*.image' => 'Il file deve essere un\'immagine',
            'images.*.mimes' => 'Il file deve essere di tipo jpeg, png, jpg, gif o svg',
            'images.*.max' => 'Le immagini sono troppo grandi, massimo :max kb',
        ];
    }
}
