<?php

include_once 'FilterInterface.php';

class EmptyStringFilter implements FilterInterface
{
    public function isEmpty(mixed $value): bool
    {
        return $value === '';
    }
}
