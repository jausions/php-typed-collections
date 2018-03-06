<?php

namespace Abacus11\Collections;

interface TypedCollection
{
    /**
     * Returns if the argument is of the collection's type
     *
     * @param mixed $element
     *
     * @return boolean
     */
    public function isElementType($element);

    /**
     * Defines the criteria for adding elements to the collection
     *
     * If a closure is passed, it needs to expect one argument and must
     * return TRUE or FALSE, for valid and invalid values respectively.
     *
     * If a string is passed, it can either be a fully qualified class name
     * or one of the following:
     * <ul>
     *  <li>array</li>
     *  <li>boolean</li>
     *  <li>callable</li>
     *  <li>double</li>
     *  <li>integer</li>
     *  <li>number</li>
     *  <li>json</li>
     *  <li>object</li>
     *  <li>resource</li>
     *  <li>string</li>
     * </ul>
     *
     * @param string|\Closure $criteria
     *
     * @return $this
     *
     * @throws \TypeError
     * @throws \Exception
     *
     * @see TypedCollection::setElementTypeLike()
     */
    public function setElementType($criteria);

    /**
     * Defines the criteria for adding elements to the collection
     *
     * The type of the argument will be used as criteria.
     *
     * @param mixed $sample
     *
     * @return $this
     *
     * @throws \TypeError
     * @throws \InvalidArgumentException
     * @throws \Exception
     *
     * @see TypedCollection::setElementType()
     */
    public function setElementTypeLike($sample);
}