# Change log

## Version 2.0.0-alpha
 - Extended support for PHP 5.6 to 8.2.
 - Added `'any'` as supported element type.
   A collection with that type will accept any value.
 - Added `'class'` as supported element type.
   A collection with that type will accept class names and interface names.
 - Added the following constants:
   - `Abacus11\Collections\TypedCollection::OF_ANYTHING`
   - `Abacus11\Collections\TypedCollection::OF_ARRAYS`
   - `Abacus11\Collections\TypedCollection::OF_BOOLEANS`
   - `Abacus11\Collections\TypedCollection::OF_CALLABLES`
   - `Abacus11\Collections\TypedCollection::OF_CLASSES_AND_INTERFACES`
   - `Abacus11\Collections\TypedCollection::OF_DOUBLES`
   - `Abacus11\Collections\TypedCollection::OF_INTEGERS`
   - `Abacus11\Collections\TypedCollection::OF_JSON_STRINGS`
   - `Abacus11\Collections\TypedCollection::OF_NUMBERS`
   - `Abacus11\Collections\TypedCollection::OF_OBJECTS`
   - `Abacus11\Collections\TypedCollection::OF_PHP_RESOURCES`
   - `Abacus11\Collections\TypedCollection::OF_STRINGS`
 - Switched dev dependencies from [fzaninotto/faker](https://github.com/fzaninotto/Faker)
   to [fakerphp/faker](https://fakerphp.github.io/).
 - Introduction of 5 exceptions:
   - `Abacus11\Collections\Exceptions\CannotChangeTypeException`
   - `Abacus11\Collections\Exceptions\InvalidArgumentTypeException`
   - `Abacus11\Collections\Exceptions\InvalidSampleException`
   - `Abacus11\Collections\Exceptions\InvalidTypeDefinitionException`
   - `Abacus11\Collections\Exceptions\TypeNotSetException`

## Version 1.0.0
 - Initial release
