<?php

namespace App\Domains\Service;

class ParserService
{
    public const NO_DESC = '暫不提供';

    public function getDescription(string $description)
    {
        return nl2br(mb_substr($description, 0, 200));
    }

    public function getSalaryDesc(int $min, int $max)
    {
        if ($min === 0 && $max === 0) {
            return self::NO_DESC;
        }

        $minDesc = '';
        $maxDesc = '';

        if ($min !== 0) {
            $min = $min / 1000;
            $minDesc = $min . 'K';
        }

        if ($max !== 0 && $max != 9999999) {
            $max = $max / 1000;
            $maxDesc = $max . 'K';
        }

        if (!empty($maxDesc)) {
            return $minDesc . '~' . $maxDesc;
        }

        return $minDesc;
    }

    public function getLocationInfo(string $location)
    {
        if (empty($location)) {
            return self::NO_DESC;
        }

        if (mb_strlen($location) <= 3) {
            return $location;
        }

        $city = mb_substr($location, 0, 3);
        $area = mb_substr($location, 3, 3);
        return $city . '</br>' . $area;
    }
}
