<?php
namespace Coercive\Utility\Iterator;

use Iterator;

/**
 * Iterate multibyte string
 *
 * @link https://stackoverflow.com/questions/3666306/how-to-iterate-utf-8-string-in-php/14366023
 * @author Chris Nasr @link https://stackoverflow.com/users/4035305/chris-nasr
 * @author Lajos Meszaros @link https://stackoverflow.com/users/1806628/lajos-meszaros
 *
 * @package		Coercive\Utility\Iterator
 * @link		https://github.com/Coercive/Iterator
 *
 * @author  	Anthony Moral <contact@coercive.fr>
 * @copyright   (c) 2018 Anthony Moral
 * @license 	MIT
 */
class MbStrIterator implements Iterator
{
	/** @var int Current cursor position */
	private $pos = 0;

	/** @var int Current char size */
	private $size = 0;

	/** @var string The source string to iterate */
	private $str = null;

	/**
	 * MbStrIterator constructor.
	 *
	 * @param string $str
	 * @return void
	 */
	public function __construct(string $str)
	{
		$this->str = $str;
		$this->calculateSize();
	}

	/**
	 * Calculate size of the current character
	 *
	 * @return void
	 */
	private function calculateSize()
	{
		# If we're done already
		if(!isset($this->str[$this->pos])) {
			return;
		}

		# Get the character at the current position
		$ascii = ord($this->str[$this->pos]);

		# If it's a single byte, set it to one
		if($ascii < 128) {
			$this->size = 1;
		}

		# Else, it's multi-byte : Figure out how long it is
		elseif($ascii < 224) {
			$this->size = 2;
		}
		elseif($ascii < 240){
			$this->size = 3;
		}
		elseif($ascii < 248){
			$this->size = 4;
		}
		elseif($ascii === 252){
			$this->size = 5;
		}
		else {
			$this->size = 6;
		}
	}

	/**
	 * Get current character
	 *
	 * @inheritdoc
	 * @see Iterator::current()
	 */
	public function current()
	{
		# If we're done
		if(!isset($this->str[$this->pos])) {
			return false;
		}

		# Else if we have one byte
		elseif($this->size === 1) {
			return $this->str[$this->pos];
		}

		# Else, it's multi-byte
		else {
			return substr($this->str, $this->pos, $this->size);
		}
	}

	/**
	 * Return the current position
	 *
	 * @inheritdoc
	 * @see Iterator::key()
	 */
	public function key()
	{
		return $this->pos;
	}

	/**
	 * Increment the position by the current size and then recalculate
	 *
	 * @inheritdoc
	 * @see Iterator::next()
	 */
	public function next()
	{
		$this->pos += $this->size;
		$this->calculateSize();
	}

	/**
	 * Reset the position and size
	 *
	 * @inheritdoc
	 * @see Iterator::rewind()
	 */
	public function rewind()
	{
		$this->pos = 0;
		$this->calculateSize();
	}

	/**
	 * Return if the current position is valid
	 *
	 * @inheritdoc
	 * @see Iterator::valid()
	 */
	public function valid()
	{
		return isset($this->str[$this->pos]);
	}
}