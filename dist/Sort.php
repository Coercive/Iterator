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

	/** @var bool If target key does not match, remove entry or add it anyway */
	private bool $strict = true;

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
	 * Main data sorting method
	 *
	 * @param string $key
	 * @param int $order
	 * @return array
	 */
	private function sort(string $key, int $order): array
	{
		if (!$this->source) {
			return [];
		}

		$sortable = [];
		foreach ($this->source as $k => $v) {
			if (is_array($v)) {
				foreach ($v as $sk => $sv) {
					if (strval($sk) === $key) {
						$sortable[$k] = $sv;
					}
				}
			}
			elseif(!$this->strict) {
				$sortable[$k] = $v;
			}
		}

		switch ($order) {
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
	 * If target key does not match, remove entry or add it anyway
	 *
	 * @param bool $enable [optional]
	 * @return $this
	 */
	public function strict(bool $enable = false): self
	{
		$this->strict = $enable;
		return $this;
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
	 * Array sort ASCENDING
	 *
	 * @param string $key
	 * @return array
	 */
	public function asc(string $key): array
	{
		return $this->sort($key, SORT_ASC);
	}

	/**
	 * Array sort DESCENDING
	 *
	 * @param string $key
	 * @return array
	 */
	public function desc(string $key): array
	{
		return $this->sort($key, SORT_DESC);
	}
}