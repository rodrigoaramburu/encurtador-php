<?php

declare(strict_types=1);

use App\Util\IpLocation;

test('deve localizar uma cidade pelo ip', function () {
    $ip = '189.40.90.175';

    $ipLocation = IpLocation::locationIso($ip);

    expect($ipLocation['country'])->toBe('BR');
    expect($ipLocation['subdivision'])->toBe('SP');
});

test('deve retornar unknown se ip não encontrado', function () {
    $ip = '192.168.0.100';

    $ipLocation = IpLocation::locationIso($ip);

    expect($ipLocation['country'])->toBe('Unknown');
    expect($ipLocation['subdivision'])->toBe('Unknown');
});