<?php

declare(strict_types=1);

namespace Optional\Tests;

use Optional\Optional;
use PHPUnit\Framework\TestCase;
use Optional\Filters\IsNullFilter;
use Optional\Filters\ZeroIntFilter;
use Optional\Filters\ZeroFloatFilter;
use Optional\Filters\EmptyArrayFilter;
use Optional\Filters\InstanceOfFilter;
use Optional\Filters\IsNegativeFilter;
use Optional\Filters\EmptyStringFilter;
use Optional\Interfaces\FilterInterface;
use Optional\Exceptions\EmptyValueException;

final class OptionalTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @dataProvider valuesData */
    public function testCanGetValue(mixed $value, mixed $expectedResult): void
    {
        $optional = new Optional($value);

        $this->assertSame($expectedResult, $optional->value());

        $optional = Optional::new($value);

        $this->assertSame($expectedResult, $optional->value());
    }

    public function valuesData(): array
    {
        $cases = [];

        $cases['int'] = [1, 1];
        $cases['float'] = [1.15, 1.15];
        $cases['bool'] = [true, true];
        $cases['array'] = [['a'], ['a']];
        $cases['string'] = ['a test', 'a test'];

        return $cases;
    }

    /** @dataProvider filterValuesData */
    public function testCanFilterValue(mixed $value, mixed $expectedResult, ?FilterInterface $filter = null): void
    {
        $optional = new Optional($value);

        if ($filter) {
            $optional->withFilter($filter);
        }

        $this->assertSame($expectedResult, $optional->orDefault('<empty>'));
    }

    public function filterValuesData(): array
    {
        $cases = [];

        $cases['int filtered 1'] = [null, '<empty>'];
        $cases['int filtered 2'] = [0, 0];
        $cases['int filtered 3'] = [0, '<empty>', new ZeroIntFilter()];

        $cases['float filtered 1'] = [0.0, 0.0];
        $cases['float filtered 2'] = [0.0, '<empty>',  new ZeroFloatFilter()];

        $cases['string filtered 1'] = ['', ''];
        $cases['string filtered 1'] = ['', '<empty>', new EmptyStringFilter()];
        $cases['string filtered 2'] = ['custom filter', '<empty>', new class implements FilterInterface {
            public function isEmpty(mixed $value): bool
            {
                return $value === 'custom filter';
            }
        }];

        return $cases;
    }

    /** @dataProvider emptyValueData */
    public function testWillThrowWhenEmptyValue(
        mixed $value,
        ?FilterInterface $filter = null
    ): void {
        $optional = new Optional($value);

        if ($filter) {
            $optional->withFilter($filter);
        }

        $this->expectException(EmptyValueException::class);
        $this->expectExceptionMessage('Empty value');

        $optional->value();
    }

    public function emptyValueData(): array
    {
        $cases = [];

        $cases['empty 1'] = [null];
        $cases['empty 2'] = [0, new ZeroIntFilter()];

        return $cases;
    }

    /** @dataProvider multipleFilters */
    public function testCanFilterWithMultipleFilters(mixed $value, array $filters): void
    {
        $this->expectException(EmptyValueException::class);

        $optional = new Optional($value);
        $optional->withFilters($filters)->value();
    }

    public function multipleFilters(): array
    {
        $cases = [];

        $cases['case 1'] = [null, []];
        $cases['case 2'] = [0, [new IsNullFilter(), new EmptyStringFilter(), new ZeroIntFilter()]];
        $cases['case 3'] = ['', [new IsNullFilter(), new EmptyStringFilter(), new ZeroIntFilter()]];
        $cases['case 4'] = [[], [new IsNullFilter(), new EmptyStringFilter(), new EmptyArrayFilter()]];
        $cases['case 5'] = [-1, [new IsNegativeFilter()]];

        return $cases;
    }

    public function testCanAddMultipleFilters(): void
    {
        $optional = new Optional(1);
        $this->assertSame(0, count($optional->getFilters()));

        $optional->addFilter(new ZeroIntFilter());
        $this->assertSame(1, count($optional->getFilters()));

        $optional->addFilter(new EmptyStringFilter());
        $this->assertSame(2, count($optional->getFilters()));

        $optional->withFilter(new EmptyStringFilter());
        $this->assertSame(1, count($optional->getFilters()));

        $optional->withFilters([new EmptyStringFilter(), new IsNegativeFilter()]);
        $this->assertSame(2, count($optional->getFilters()));
        $optional->addFilter(new EmptyStringFilter());
        $this->assertSame(2, count($optional->getFilters()));
        $optional->addFilter(new ZeroIntFilter());
        $this->assertSame(3, count($optional->getFilters()));

        $optional->withFilter(new EmptyStringFilter());
        $this->assertSame(1, count($optional->getFilters()));
        $optional->addFilters([new ZeroIntFilter(), new IsNegativeFilter()]);
        $this->assertSame(3, count($optional->getFilters()));
    }

    public function testInstanceOfFilter(): void
    {
        $optional = new Optional(null);
        $value = $optional->withFilter(new InstanceOfFilter());
        $this->assertSame('<empty>', $value->orDefault('<empty>'));

        $obj = new Test();
        $optional = new Optional($obj);
        $value = $optional->withFilter(new InstanceOfFilter(Test::class));
        $this->assertSame($obj, $value->orDefault('<empty>'));
    }
}

class Test
{
}
