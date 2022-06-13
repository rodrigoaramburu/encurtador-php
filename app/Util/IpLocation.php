<?php

declare(strict_types=1);

namespace App\Util;

use GeoIp2\Database\Reader;

final class IpLocation
{
    /**
     * @return array<string,string>
     */
    public static function locationIso(string $ip): array
    {
        $reader = new Reader(__DIR__ . '/../../resources/GeoLite2-City.mmdb');
        try {
            $record = $reader->city($ip);
            return [
                'country' => $record->country->isoCode,
                'subdivision' => $record->mostSpecificSubdivision->isoCode,
            ];
        } catch (\Exception $e) {
            return [
                'country' => 'Unknown',
                'subdivision' => 'Unknown',
            ];
        }
    }
}
