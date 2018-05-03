<?php

namespace Abacus11\Collections\Exception;

if (class_exists('TypeError')) {
    class InvalidTypeDefinitionException extends \TypeError {}
} else {
    class InvalidTypeDefinitionException extends \InvalidArgumentException {}
}
