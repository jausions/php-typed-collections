<?php

namespace Abacus11\Collections;

class CollectionOfArrays extends TypedArrayCollection
{
    /**
     * Collection of arrays
     *
     * Each element of the collection is an array
     *
     * @param array[] $elements
     *
     * @throws \AssertionError
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct(array $elements = [])
    {
        $this->setElementType('array');
        parent::__construct($elements);
    }
}