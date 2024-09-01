<?php

namespace Optional;

use Optional\Filters\IsNullFilter;
use Optional\Interfaces\FilterInterface;
use Optional\Exceptions\EmptyValueException;

/**
 * Simple class to add Optional type for mixed values.
 * If there are no other filters added, null check is the default check.
 * Additionally to this check you can add your own filters classes.
 *
 * @author Rimantas R. <eteris@gmail.com>
 */
class Optional
{
    /** @var array<FilterInterface> */
    private array $filters = [];

    public function __construct(private mixed $value)
    {
    }

    public static function new(mixed $value): self
    {
        return new Optional($value);
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
            if (
                $filter instanceof FilterInterface &&
                !in_array($filter, $this->filters)
            ) {
                $this->filters[] = $filter;
            }
        }

        return $this;
    }

    /**
     * @return array<FilterInterface>
     */
    public function getFilters(): array
    {
        return $this->filters;
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
            $this->withFilter(new IsNullFilter());
        }

        foreach ($this->filters as $filter) {
            if ($filter->isEmpty($this->value)) {
                return true;
            }
        }

        return false;
    }
}
