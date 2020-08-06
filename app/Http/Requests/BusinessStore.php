<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessStore extends FormRequest
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
            'name' => 'required|max:255',
            'vatid' => 'required|string',
            'address' => 'required|max:255',
            'city' => 'required|max:255',
            'zip' => 'required|string|max:12',
            'country' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'description' => 'required|string',
            'accept_terms' => 'required',
            'telephone' => 'numeric',
            'instagram' => 'nullable|max:255|url',
            'website' => 'nullable|max:255|url',
            'facebook' => 'nullable|max:255|url',
            'twitter' => 'nullable|max:255|url'
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
            'name.required' => __('A name is required'),
            'vatid.required'  => __('Vatid is required'),
            'address.required' => __('Address is required'),
            'city.required' => __('City is required'),
            'zip.required' => __('Zip is required'),
            'country.required' => __('Country is required'),
            'category_id.required' => __('Business category is required'),
            'description.required' => __('Please enter your business description'),
            'accept_terms.required' => _('Please accept terms and conditions.')
        ];
    }
}
