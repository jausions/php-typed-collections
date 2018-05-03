<?php

namespace Abacus11\Collections;

interface TypedCollection
{
    const OF_ANYTHING = 'any';
    const OF_ARRAYS = 'array';
    const OF_BOOLEANS = 'boolean';
    const OF_CALLABLES = 'callable';
    const OF_CLASSES_AND_INTERFACES = 'class';
    const OF_DOUBLES = 'double';
    const OF_INTEGERS = 'integer';
    const OF_JSON_STRINGS = 'json';
    const OF_NUMBERS = 'number';
    const OF_OBJECTS = 'object';
    const OF_PHP_RESOURCES = 'resource';
    const OF_STRINGS = 'string';


    /**
     * Returns whether the argument is of the collection's type
     *
     * @param mixed $element
     *
     * @return boolean
     */
    public function isElementType($element);

    /**
     * Returns whether the collection has a type
     *
     * @return boolean
     */
    public function isElementTypeSet();

    /**
     * Defines the criteria for adding elements to the collection
     *
     * If a closure is passed, it needs to expect one argument and must
     * return TRUE or FALSE, for valid and invalid values respectively.
     *
     * If a string is passed, it can either be a fully qualified class name
     * or one of the following:
     * <ul>
     *  <li><tt>'any'</tt>: Anything would be accepted.</li>
     *  <li><tt>'array'</tt>: Only arrays would be accepted.</li>
     *  <li><tt>'boolean'</tt>: Only booleans would be accepted.</li>
     *  <li><tt>'callable'</tt>: Only PHP-callable would be accepted (closure, [Class, method], and so on)</li>
     *  <li><tt>'class'</tt>: Only class and interface names would be accepted. Do not confuse this with passing a specific class name.</li>
     *  <li><tt>'double'</tt>: Only doubles would be accepted.</li>
     *  <li><tt>'integer'</tt>: Only integers would be accepted.</li>
     *  <li><tt>'json'</tt>: Only valid JSON strings would be accepted.</li>
     *  <li><tt>'number'</tt>: Only numbers would be accepted.</li>
     *  <li><tt>'object'</tt>: Any objects would be accepted.</li>
     *  <li><tt>'resource'</tt>: Only PHP resources would be accepted</li>
     *  <li><tt>'string'</tt>: Only strings would be accepted.</li>
     * </ul>
     *
     * @param string|\Closure $criterion
     *
     * @return self
     *
     * @see TypedCollection::setElementTypeLike()
     */
    public function setElementType($criterion);

    /**
     * Defines the criteria for adding elements to the collection
     *
     * The type of the argument will be used as criteria.
     *
     * @param mixed $sample
     *
     * @return self
     *
     * @see TypedCollection::setElementType()
     */
    public function setElementTypeLike($sample);
}
