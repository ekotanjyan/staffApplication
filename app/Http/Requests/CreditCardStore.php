<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditCardStore extends FormRequest
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
    	$rules = [];
		if ($this->isMethod('POST')) {
			$rules = [
				'stripeToken' => 'required|max:50'
			];
		}
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
            'stripeToken.required'  => __('A stripeToken required'),
		];
    }
}
