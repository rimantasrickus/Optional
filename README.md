# Optional

Simple class to add Optional type for scalar values. \
Before returning value it will always check if value is null. \
Additionally to this check you can add your own filter class to check for example for empty strings.

```PHP
<?php

include 'Optional.php';
include 'ZeroIntFilter.php';

$optional = new Optional(rand(0, 1));

printf(
    'value: %s',
    $optional
        ->withFilter(new ZeroIntFilter())
        ->orEmpty('empty')
);
