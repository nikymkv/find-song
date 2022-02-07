<?php

namespace App\Http\Requests\Song;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'featuring_with' => 'nullable|string|min:2|max:255',
            'producer' => 'required|string|min:2|max:255',
            'text_written_by' => 'required|string|min:2|max:255',
            'music_written_by' => 'required|string|min:2|max:255',
            'mixed_by' => 'required|string|min:2|max:255',
            'genre_id' => 'required|integer|exists:genres,id',
            'user_id' => 'required|integer',
            'text' => 'required|string|min:128|max:32768',
            'preview_image' => 'nullable|image'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id,
        ]);
    }
}
