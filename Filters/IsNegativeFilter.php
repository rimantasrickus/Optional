<?php

include_once 'FilterInterface.php';

class IsNegativeFilter implements FilterInterface
{
    public function isEmpty(mixed $value): bool
    {
        return $value < 0;
    }
}
