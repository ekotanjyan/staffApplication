<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductImageStore extends FormRequest
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
      $rules = [
          'image_input' => 'required|image|mimes:jpeg,jpg,png'
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function __messages()
    {
      return [
          'image_input' => __('An image is required'),
          'image_input.mimes' => __('Please select a correct format (jpeg,bmp,png)'),
          'image_input.size' => __('Please do not exceed file size of 5MB')
        ];
    }
}
