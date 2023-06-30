<?php
namespace Coercive\Utility\Iterator;

/**
 * Array sort by specific key
 *
 * @link https://www.php.net/manual/en/function.sort.php#99419
 * @author phpdotnet at m4tt dot co dot uk
 *
 * @package		Coercive\Utility\Iterator
 * @link		https://github.com/Coercive/Iterator
 *
 * @author  	Anthony Moral <contact@coercive.fr>
 * @copyright   (c) 2023 Anthony Moral
 * @license 	MIT
 */
class Sort
{
	/** @var array The given input array */
	private array $source;

	/** @var bool Insensitive case flags for sorting array */
	private bool $insensitive = false;

	/** @var int SORT_ASC | SORT_DESC */
	private int $order = 0;

	/** @var int Targeted key level for sorting array */
	private int $level = 0;

	/**
	 * Specific sorting flag for insensitive case
	 *
	 * @return int
	 */
	private function getSortFlags(): int
	{
		return $this->insensitive ? SORT_STRING | SORT_FLAG_CASE : SORT_REGULAR;
	}

	/**
	 * Main method recursive search key for sorting array
	 *
	 * @param string $key
	 * @param array $array
	 * @param array|null $sortable [reference]
	 * @param string|null $mainKey [reference]
	 * @param int $level [optional]
	 * @return void
	 */
	private function find(string $key, array $array, ? array &$sortable = null, ? string &$mainKey = null, int $level = 0)
	{
		if(null === $sortable) {
			$sortable = [];
		}
		foreach ($array as $k => $v) {
			$k = strval($k);
			if($level === 0) {
				$mainKey = $k;
			}
			if ($k === $key && (!$this->level || $this->level === $level)) {
				if(!array_key_exists($mainKey, $sortable)) {
					$sortable[$mainKey] = $v;
				}
				elseif($this->order === SORT_ASC && (!$this->insensitive && $sortable[$mainKey] > $v
						|| $this->insensitive && strcasecmp($sortable[$mainKey], $v) > 0)) {
					$sortable[$mainKey] = $v;
				}
				elseif($this->order === SORT_DESC && (!$this->insensitive && $sortable[$mainKey] < $v
						|| $this->insensitive && strcasecmp($sortable[$mainKey], $v) < 0)) {
					$sortable[$mainKey] = $v;
				}
			}
			elseif (is_array($v) && (!$this->level || $this->level > $level)) {
				$this->find($key, $v, $sortable, $mainKey, $level + 1);
			}
		}
	}

	/**
	 * Main data sorting method
	 *
	 * @param string $key
	 * @return array
	 */
	private function sort(string $key): array
	{
		if (!$this->source) {
			return [];
		}

		$this->find($key, $this->source, $sortable);

		switch ($this->order) {
			case SORT_ASC:
				asort($sortable, $this->getSortFlags());
				break;
			case SORT_DESC:
				arsort($sortable, $this->getSortFlags());
				break;
		}

		$new = [];
		foreach ($sortable as $k => $v) {
			$new[$k] = $this->source[$k];
		}
		return $new;
	}

	/**
	 * Sort constructor.
	 *
	 * @param array $array
	 * @return void
	 */
	public function __construct(array $array)
	{
		$this->source = $array;
	}

	/**
	 * Case insensitive for sorting values
	 *
	 * @param bool $enable [optional]
	 * @return $this
	 */
	public function insensitive(bool $enable = true): self
	{
		$this->insensitive = $enable;
		return $this;
	}

	/**
	 * Key level for sorting values
	 *
	 * @param int $level [optional]
	 * @return $this
	 */
	public function level(int $level = 0): self
	{
		$this->level = $level;
		return $this;
	}

	/**
	 * Array sort ASCENDING
	 *
	 * @param string $key
	 * @param int|null $level [optional]
	 * @return array
	 */
	public function asc(string $key, ? int $level = null): array
	{
		if(null !== $level) {
			$this->level($level);
		}
		$this->order = SORT_ASC;
		return $this->sort($key);
	}

	/**
	 * Array sort DESCENDING
	 *
	 * @param string $key
	 * @param int|null $level [optional]
	 * @return array
	 */
	public function desc(string $key, ? int $level = null): array
	{
		if(null !== $level) {
			$this->level($level);
		}
		$this->order = SORT_DESC;
		return $this->sort($key);
	}
}