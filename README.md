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
    'badEntry1' => 'error1',
    'badEntry2' => 'error2',
);

# Instantiate with data
$array = new Sort($examples);

# Set insensitive if useful
$array->insensitive(true);

# Set insensitive if useful
$array->strict(true);

# Sort ASCENDING
$sorted = $array->asc('id');

# Sort DESCENDING
$sorted = $array->desc('id');

```