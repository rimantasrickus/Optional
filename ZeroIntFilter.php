<?php

include_once 'FilterInterface.php';

class ZeroIntFilter implements FilterInterface
{
    public function isEmpty(string|int|float|null $value): bool
    {
        return $value === 0;
    }
}
