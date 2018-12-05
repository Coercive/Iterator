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