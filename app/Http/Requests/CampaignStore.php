<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AtLeastOneTranslationRequired;

class CampaignStore extends FormRequest
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
          'translations' => new AtLeastOneTranslationRequired(['name', 'description']),
          'translations.*.name' => ['required_with:translations.*.description', 'nullable', 'string'],
          'translations.*.description' => ['required_with:translations.*.name', 'nullable', 'string'],
          'start_date' => 'nullable|date|after:yesterday',
          'end_date' => 'nullable|date|after:today|after:start_date',
          'target' => 'required|numeric|min:100|max:100000',
          'business_id' => 'required|integer',
          'image' => 'required|image|mimes:jpeg,jpg,png',
          //'products' => "required|array|min:1",
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
            'translations.*.name.required_with' => __('Your campaign name is required'),
            'translations.*.description.required_with' => __('Please enter your campaign description'),
        ];
    }
}
