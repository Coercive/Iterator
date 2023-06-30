Coercive Utility Iterator
=========================

Item iterator

Get
---
```
composer require coercive/iterator
```

Example Multibyte String Iterator
---------------------------------
```php
foreach (new MbStrIterator('string') as $i => $chr) {
	echo "$i => $chr \n";
}
```

Example Array Sort
------------------
```php
$examples = array(
    1 => array(
        'id' => 'abcd',
    ),
    2 => array(
        'id' => 'ABCD',
    ),
    3 => array(
        'id' => 'aabcd',
    ),
    4 => array(
        'id' => 'aAbcd',
    ),
    5 => array(
        'id' => 'AAbcd',
    ),
);

# Instantiate with data
$array = new Sort($examples);

# [optional] Set case-insensitive if useful
$array->insensitive(true);

# [optional] Set targeted key level
$array->level(1);

# Sort ASCENDING
$sorted = $array->asc('id');

# Sort DESCENDING
$sorted = $array->desc('id');

```