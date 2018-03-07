<?php

namespace Abacus11\Collections;

/**
 * Trait to implement constraints on elements of an ArrayAccess interface
 * implementation
 *
 * @author Philippe Jausions <Philippe.Jausions@11abacus.com>
 * @see \ArrayAccess
 */
trait TypedArrayAccessTrait
{
    use TypedCollectionTrait;

    /**
     * Sets an element in the collection at the specified key/index.
     *
     * @param mixed $offset The key/index of the element to set.
     * @param mixed $value The element to set.
     *
     * @return void
     *
     * @throws \TypeError when the value doesnt match the criteria
     * @throws \AssertionError when the criteria is not set
     */
    public function offsetSet($offset, $value): void
    {
        if (!$this->isElementType($value)) {
            throw new \TypeError('The value does not comply with the criteria for the collection.');
        }
        parent::offsetSet($offset, $value);
    }
}