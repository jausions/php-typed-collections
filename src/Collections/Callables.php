<?php

namespace Abacus11\Collections;

class Callables extends ArrayCollectionOf
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
        parent::__construct('callable', $elements);
    }
}