<?php

 // Autoload files using Composer autoload
require_once __DIR__ . '/../vendor/autoload.php';

use Mtabe\Replacer;

$params = [
  'name'       => 'jonh snow',
  'start_date' => '12-01-2019',
  'end_date'   => '12-12-2019'
];

$query = "SELECT * FROM users WHERE first_name = '#P{name}' AND start_date = '#P{start_date}' AND end_date = '#P{end_date}'";

$response = Replacer::replaceWithParams($query, $params);

echo $response;
