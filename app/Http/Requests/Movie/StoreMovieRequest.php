<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;


class StoreMovieRequest extends FormRequest
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
            'name' => 'required|between:3,255',
            'uploaded_file' => 'required|file|max:5120|mimetypes:video/*',
        ];
    }

}
