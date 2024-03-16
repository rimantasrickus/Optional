<?php

include_once 'FilterInterface.php';

class InstanceOfFilter implements FilterInterface
{
    public function __construct(
        private readonly ?string $instance = null
    ) {
    }

    public function isEmpty(mixed $value): bool
    {
        if ($this->instance) {
            return !$value instanceof $this->instance;
        }

        return is_null($value);
    }
}
