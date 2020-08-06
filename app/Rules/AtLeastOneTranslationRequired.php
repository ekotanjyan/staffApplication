<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AtLeastOneTranslationRequired implements Rule
{
    public $requiredFields = false;
    public $failedTranslations = [];
    public $isOneLanguageTranslated = false;
    public $message = false;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($required)
    {
      //
      $this->requiredFields = $required;
    }

    public function filterFieldsByRequiredKeys($filterKeys, $arrayToFilter){
      $out = [];
      foreach( $arrayToFilter as $fieldKey => $fieldValue ){
        if( in_array($fieldKey, $this->requiredFields) ){
          $out[$fieldKey] = $arrayToFilter[$fieldKey];
        }
      }
      return $out;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  array  $languages
     * @return bool
     */
    public function passes($attribute, $languages)
    {

      foreach( $languages as $localeKey => $fields ){

        //get an array with only the fields that are required to validate
        $filteredRequiredfields = $this->filterFieldsByRequiredKeys($this->requiredFields, $fields);

        $hasNullField = in_array(null, $filteredRequiredfields);
        $areAllfieldsNull = empty(array_filter($filteredRequiredfields));

        //if all fields for this language are null, it is fine with us...!
        if( $hasNullField && !$areAllfieldsNull ){
          $this->failedTranslations[] = $localeKey;
        }

        //...but we demand at least one translation to be completed!
        if( !$hasNullField ){
          $this->isOneLanguageTranslated = true;
        }
      }

      // Some translations are partially completed?
      // Notify the user to go and check the errors.
      if( !empty( $this->failedTranslations ) ){
        //wrap field names with a <strong/> tag
        $failedTranslations = implode(",", array_map(function($item) {
            $localeName = ucfirst( supportedLocales()[$item]['native'] );
            return '<strong>' . $localeName . '</strong>';
        }, $this->failedTranslations));
        $failedTranslations = explode(',', $failedTranslations);

        $this->message = __('Please fix errors in :languages translations.', ['languages'=>implode(' and ', $failedTranslations)] ) ;
        return false;
      }

      // None of the translations are filled?
      // We'll hold the user hostage until they obey to fill at least one translation :)
      if( ! $this->isOneLanguageTranslated ){
        $this->message = __('At least one translation is required.');
        return false;
      }

      //all is fine, sun is shining bright ;)
      return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
