<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AtLeastOneTranslationRequired;

class ProductUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //TODO check if the authenticated user actually has the authority to update a given resource.
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
          'translations' => new AtLeastOneTranslationRequired(['title', 'description']),
          'translations.*.title' => ['required_with:translations.*.description', 'nullable', 'string'],
          'translations.*.description' => ['required_with:translations.*.title', 'nullable', 'string'],
          'units' => 'required|integer',
          'price' => 'required',
          'start_date' => 'required|date',
          'end_date' => 'nullable|date|after:today|after:start_date',
          'category_id' => 'required|exists:product_categories,id',
          //'image' => 'sometimes|string'
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'translations.*.title.required_with' => __('Product name is required.'),
            'translations.*.description.required_with' => __('Product description is required.'),
        ];
    }
}
