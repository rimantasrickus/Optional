<?php

include_once 'FilterInterface.php';

class ZeroIntFilter implements FilterInterface
{
    public function isEmpty(mixed $value): bool
    {
        return $value === 0;
    }
}
