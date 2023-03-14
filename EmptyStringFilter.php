<?php

include 'FilterInterface.php';

class EmptyStringFilter implements FilterInterface
{
    public function isEmpty(string|int|float|null $value): bool
    {
        return $value === '';
    }
}
