<?php

return [
  'locale' => env('locale', 'en'),
  'image' => array(
    'maxWidth'  => 1200,
    'maxHeight' =>  1000,
    'sizes'     => array(
      'thumb'     => [300, 250],
      'medium'    => [600, 500],
      'large'     => [900, 750],
      'full'      => [1200, 1000]
    ),
  )
];
