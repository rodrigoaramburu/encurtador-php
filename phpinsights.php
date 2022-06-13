<?php

declare(strict_types=1);

return [
    'preset' => 'default',
    'remove' => [
        \SlevomatCodingStandard\Sniffs\Classes\SuperfluousExceptionNamingSniff::class,
        SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff::class,
    ],
];
