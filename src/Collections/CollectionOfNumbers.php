<?php

namespace Abacus11\Collections;

class CollectionOfNumbers extends TypedArrayCollection
{
    /**
     * Collection of numbers
     *
     * Each element of the collection is a number
     *
     * @param number[] $elements
     *
     * @throws \AssertionError
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct(array $elements = [])
    {
        $this->setElementType('number');
        parent::__construct($elements);
    }
}