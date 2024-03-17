<?php

namespace Optional\Filters;

use Optional\Interfaces\FilterInterface;

class ZeroIntFilter implements FilterInterface
{
    public function isEmpty(mixed $value): bool
    {
        return $value === 0;
    }
}
