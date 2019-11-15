<?php

namespace App\Domains\Service;

class ParserService
{
    public const NO_DESC = '暫不提供';

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
}
