<?php

namespace Abacus11\Collections;

class CollectionOfStrings extends TypedArrayCollection
{
    /**
     * Collection of strings
     *
     * Each element of the collection is a string
     *
     * @param string[] $elements
     *
     * @throws \AssertionError
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct(array $elements = [])
    {
        $this->setElementType('string');
        parent::__construct($elements);
    }
}