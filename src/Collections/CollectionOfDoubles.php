<?php

namespace Abacus11\Collections;

class CollectionOfDoubles extends TypedArrayCollection
{
    /**
     * Collection of doubles
     *
     * Each element of the collection is a double (also known as float in PHP.)
     *
     * @param double[] $elements
     *
     * @throws \AssertionError
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct(array $elements = [])
    {
        $this->setElementType('double');
        parent::__construct($elements);
    }
}