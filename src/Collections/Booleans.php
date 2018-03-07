<?php

namespace Abacus11\Collections;

class Booleans extends ArrayOf
{
    /**
     * Collection of booleans
     *
     * Each element of the collection is a boolean
     *
     * @param boolean[] $elements
     *
     * @throws \AssertionError
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct(array $elements = [])
    {
        $this->setElementType('boolean');
        parent::__construct($elements);
    }
}