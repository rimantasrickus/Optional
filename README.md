# Optional

Simple class to add Optional type for scalar values. \
Before returning value it will always check if value is null. \
Additionally to this check you can add your own filter class to check for example for empty strings.

```PHP
<?php

use Optional\Optional;
use Optional\Filters\ZeroIntFilter;

require_once 'vendor/autoload.php';

$optional = new Optional(rand(0, 1));

printf(
    "value: %s\n",
    $optional
        ->withFilter(new ZeroIntFilter())
        ->orDefault('empty')
);
