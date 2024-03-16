<?php

include_once 'FilterInterface.php';

class EmptyArrayFilter implements FilterInterface
{
    public function isEmpty(mixed $value): bool
    {
        return $value === [];
    }
}
