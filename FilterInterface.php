<?php

interface FilterInterface
{
    public function isEmpty(string|int|float|null $value): bool;
}
