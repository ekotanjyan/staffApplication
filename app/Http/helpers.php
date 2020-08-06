<?php
if (! function_exists('strlimit')) {
  function strlimit($value, $limit = 100, $end = '...')
  {
    if (mb_strwidth($value, 'UTF-8') <= $limit) {
      return $value;
    }
    return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')).$end;
  }
}

if (! function_exists('getStorageImage')) {
  function getStorageImage($model, $imageName, $width=null, $height=null)
  {

    $assetsFolderName = strtolower(class_basename($model));

    $imgURL = Thumbnail::thumb($assetsFolderName.'/'.$imageName, $width, $height);

    return $imgURL;
  }
}


if (! function_exists('supportedLocales')) {
  function supportedLocales(){
    return LaravelLocalization::getSupportedLocales();
  }
}


if (! function_exists('arrayRemoveNullValues')) {
  /**
   * Generate unique city slug from city name
   * @param  array $name
   * @return array
   */
  function arrayRemoveNullValues($array)
  {
    $data = array_map('array_filter', $array);
    return array_filter($data);
  }
}

/**
 * Generate unique city slug from city name
 * @param  object $model the model for which we cant to remove/clean translations
 * @param  array $requiredFields fields that must never be null
 * @param  array $translationsArray translations coming from the form
 * @return void
 */
if (! function_exists('removeNullTranslations')) {
  function removeNullTranslations($model, $requiredFields, $translationsArray){

    $nullTranslations = array_filter($translationsArray, function($translation, $localeKey) use ($requiredFields) {
      foreach( $requiredFields as $fieldKey ){
        if( $translation[$fieldKey] === null ){
          return $localeKey;
        }
      }
    }, ARRAY_FILTER_USE_BOTH );

    $nullTranslationLocaleKeys = array_keys($nullTranslations);

    if( ! empty($nullTranslationLocaleKeys) ){
      $model->deleteTranslations( $nullTranslationLocaleKeys );
    }

  }
}



if (! function_exists('localRoute')) {
  function localRoute($routeName, $params=[], $locale=null)
  {
      return LaravelLocalization::getLocalizedURL( $locale, route($routeName, $params));
  }
}
