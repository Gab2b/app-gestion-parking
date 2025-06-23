<?php
return array (
  'places' => 100,
  'max_consecutive_hour' => 72,
  'parking_rate' => 
  array (
    0 => 
    array (
      'day' => 'monday',
      'prices' => 3,
    ),
    1 => 
    array (
      'day' => 'tuesday',
      'prices' => 
      array (
        0 => 
        array (
          'from' => '00:00',
          'to' => '15:00',
          'price' => 2,
        ),
        1 => 
        array (
          'from' => '15:00',
          'to' => '18:00',
          'price' => 1,
        ),
        2 => 
        array (
          'from' => '18:53',
          'to' => '23:59',
          'price' => 0,
        ),
      ),
    ),
    2 => 
    array (
      'day' => 'wednesday',
      'prices' => 
      array (
        0 => 
        array (
          'from' => '00:00',
          'to' => '18:00',
          'price' => 1,
        ),
        1 => 
        array (
          'from' => '18:00',
          'to' => '23:59',
          'price' => 3,
        ),
      ),
    ),
    3 => 
    array (
      'day' => 'thursday',
      'prices' => 3,
    ),
    4 => 
    array (
      'day' => 'friday',
      'prices' => 2,
    ),
    5 => 
    array (
      'day' => 'saturday',
      'prices' => 
      array (
        0 => 
        array (
          'from' => '00:00',
          'to' => '12:00',
          'price' => 0,
        ),
        1 => 
        array (
          'from' => '12:00',
          'to' => '23:59',
          'price' => 0,
        ),
      ),
    ),
    6 => 
    array (
      'day' => 'sunday',
      'prices' => 
      array (
        0 => 
        array (
          'from' => '00:00',
          'to' => '19:00',
          'price' => 5,
        ),
        1 => 
        array (
          'from' => '19:00',
          'to' => '23:59',
          'price' => 10,
        ),
      ),
    ),
  ),
  'opening_hour' => '08:00',
  'closing_hour' => '20:00',
  'price_per_hour' => '1',
);
