<?php

include_once 'EmptyValueException.php';

/**
 * Simple class to add Optional type for scalar values.
 * Before returning value it will always check if value is null.
 * Additionally to this check you can add your own filter class to check for example for empty strings.
 *
 * @author Rimantas R. <rickus@ninepoint.consulting>
 */
class Optional
{
    private array $filters = [];

    public function __construct(
        private mixed $value,
    ) {
    }

    public function withFilter(FilterInterface $filter): self
    {
        $this->withFilters([$filter]);

        return $this;
    }

    public function addFilter(FilterInterface $filter): self
    {
        $this->addFilters([$filter]);

        return $this;
    }

    /**
     * @param array<FilterInterface> $filters
     *
     * @return self
     */
    public function withFilters(array $filters): self
    {
        $this->filters = [];
        $this->addFilters($filters);

        return $this;
    }

    /**
     * @param array<FilterInterface> $filters
     *
     * @return self
     */
    public function addFilters(array $filters): self
    {
        foreach ($filters as $filter) {
            if ($filter instanceof FilterInterface) {
                $this->filters[] = $filter;
            }
        }

        return $this;
    }

    public function orDefault(mixed $default): mixed
    {
        if ($this->isEmpty()) {
            return $default;
        }

        return $this->value;
    }

    /**
     * @throws EmptyValueException
     */
    public function value(): mixed
    {
        if ($this->isEmpty()) {
            throw new EmptyValueException("Empty value");
        }

        return $this->value;
    }

    public function isEmpty(): bool
    {
        if (count($this->filters) === 0) {
            return is_null($this->value);
        }

        foreach ($this->filters as $filter) {
            if ($filter->isEmpty($this->value)) {
                return true;
            }
        }

        return false;
    }
}
