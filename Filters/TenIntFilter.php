<?php

include_once 'FilterInterface.php';

class TenIntFilter implements FilterInterface
{
    public function isEmpty(mixed $value): bool
    {
        return $value === 10;
    }
}
