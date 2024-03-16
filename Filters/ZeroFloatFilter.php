<?php

include_once 'FilterInterface.php';

class ZeroFloatFilter implements FilterInterface
{
    public function isEmpty(mixed $value): bool
    {
        return $value === 0.0;
    }
}
