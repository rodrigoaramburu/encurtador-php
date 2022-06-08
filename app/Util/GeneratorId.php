<?php 

declare(strict_types=1);

namespace App\Util;


class GeneratorId
{
    private static string $characteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function generate(int $size = 6): string
    {
        $id = '';
        $max = strlen(self::$characteres) - 1;
        for($i = 0; $i < $size; $i++ ){
            $id .= self::$characteres[rand(0, $max)];
        }
        return $id;
    }
}