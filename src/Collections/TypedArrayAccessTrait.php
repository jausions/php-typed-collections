<?php

namespace Abacus11\Collections;

use Abacus11\Collections\Exception\InvalidArgumentTypeException;
use Abacus11\Collections\Exception\TypeNotSetException;

/**
 * Trait to implement constraints on elements of an ArrayAccess interface
 * implementation.
 *
 * @see \ArrayAccess
 */
trait TypedArrayAccessTrait
{
    use TypedCollectionTrait;

    /**
     * @var array
     */
    protected $elements = [];

    /**
     * Sets an element in the collection at the specified key/index.
     *
     * @param mixed $offset The key/index of the element to set.
     * @param mixed $value The element to set.
     *
     * @return void
     *
     * @throws InvalidArgumentTypeException when the value does not match the criterion of the collection
     * @throws TypeNotSetException when the criterion of the collection is not set
     */
    public function offsetSet($offset, $value)
    {
        if (!$this->isElementType($value)) {
            throw new InvalidArgumentTypeException('The value does not comply with the criterion for the collection.');
        }
        // Support appending to an array (in that case $offset is null)
        if (null === $offset) {
            $this->elements[] = $value;
            return;
        }

        $this->elements[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->elements);
    }

    public function offsetGet($offset)
    {
        return $this->elements[$offset];
    }

    public function offsetUnset($offset)
    {
        unset($this->elements[$offset]);
    }
}
