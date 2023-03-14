<?php

/**
 * Simple class to add Optional type for scalar values.
 * Before returning value it will always check if value is null.
 * Additionally to this check you can add your own filter class to check for example for empty strings.
 *
 * @author Rimantas R. <rickus@ninepoint.consulting>
 */
class Optional
{
    public function __construct(
        private string|int|float|null $value,
        private ?FilterInterface $filter = null
    ) {
    }

    public function withFilter(FilterInterface $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    public function orEmpty(string|int|float $default)
    {
        if ($this->isEmpty()) {
            return $default;
        }

        return $this->value;
    }

    public function orThrow()
    {
        if ($this->isEmpty()) {
            throw new \Exception("Empty value");
        }

        return $this->value;
    }

    protected function isEmpty()
    {
        if ($this->filter) {
            return $this->filter->isEmpty($this->value) || is_null($this->value);
        }

        return is_null($this->value);
    }
}
