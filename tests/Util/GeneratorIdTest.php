<?php

declare(strict_types=1);

use App\Util\GeneratorId;

test('deve gerar um id alfabetico', function(){

    $id = GeneratorId::generate();

    $expectedChars = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    
    expect(strlen($id))->toBe(6);
    expect($id[0])->toBeIn($expectedChars);
    expect($id[1])->toBeIn($expectedChars);
    expect($id[2])->toBeIn($expectedChars);
    expect($id[3])->toBeIn($expectedChars);
    expect($id[4])->toBeIn($expectedChars);
    expect($id[5])->toBeIn($expectedChars);
});