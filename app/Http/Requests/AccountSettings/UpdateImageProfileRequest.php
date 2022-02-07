<?php

namespace App\Http\Requests\AccountSettings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImageProfileRequest extends FormRequest
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
            'preview_image' => 'required|image',
        ];
    }
}
