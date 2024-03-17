<?php

namespace Optional\Interfaces;

interface FilterInterface
{
    public function isEmpty(mixed $value): bool;
}
