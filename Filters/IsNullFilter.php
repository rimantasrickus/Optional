<?php

include_once 'FilterInterface.php';

class IsNullFilter implements FilterInterface
{
    public function isEmpty(mixed $value): bool
    {
        return is_null($value);
    }
}
