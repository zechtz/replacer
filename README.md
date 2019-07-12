# About

A simple php library that helps replace sql query with given params

The test is included in the tests directory

# Using the libary

```php
composer require mtabe/replacer
```

Then in your project include it

```php 
use Mtabe\Replacer;
```

```php
$params = [
  'name'       => 'jonh snow',
  'start_date' => '12-01-2019',
  'end_date'   => '12-12-2019'
];

$query = "SELECT * FROM users WHERE first_name = '#P{name}' AND start_date = '#P{start_date}' AND end_date = '#P{end_date}'";

$response = Replacer::replaceWithParams($query, $params);

will return
SELECT * FROM users WHERE first_name = 'jonh snow' AND start_date = '12-01-2019' AND end_date = '12-12-2019'
```
