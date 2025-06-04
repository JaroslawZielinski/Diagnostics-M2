<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    public static function dateDaysDiff(\DateTime $dateTime1, \DateTime $dateTime2): int
    {
        $diff = $dateTime1->diff($dateTime2);
        return (int)$diff->format('%r%a');
    }

    public static function prefixStringArray(?array $input, string $prefix = ''): ?array
    {
        if (empty($input)) {
            return null;
        }
        return array_values(array_map(function ($value) use ($prefix) {
            $domain = reset($value);
            return $prefix . $domain;
        }, $input));
    }
}
