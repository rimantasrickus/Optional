<?php

namespace Optional\Filters;

use Optional\Interfaces\FilterInterface;

class IsNullFilter implements FilterInterface
{
    public function isEmpty(mixed $value): bool
    {
        return is_null($value);
    }
}
