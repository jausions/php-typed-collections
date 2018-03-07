<?php

namespace Abacus11\Collections;

class Callables extends ArrayOf
{
    /**
     * Collection of callables
     *
     * Each element of the collection is a callable
     *
     * @param callable[] $elements
     *
     * @throws \AssertionError
     * @throws \Exception
     * @throws \TypeError
     */
    public function __construct(array $elements = [])
    {
        $this->setElementType('callable');
        parent::__construct($elements);
    }
}