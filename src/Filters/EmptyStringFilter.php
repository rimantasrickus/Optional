<?php

namespace Optional\Filters;

use Optional\Interfaces\FilterInterface;

class EmptyStringFilter implements FilterInterface
{
    public function isEmpty(mixed $value): bool
    {
        return $value === '';
    }
}
