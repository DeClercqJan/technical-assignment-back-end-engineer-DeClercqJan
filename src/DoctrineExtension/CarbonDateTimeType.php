<?php
declare(strict_types=1);

namespace App\DoctrineExtension;

use Carbon\Carbon;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeType;

class CarbonDateTimeType extends DateTimeType
{
    const CARBON_DATE_TIME = 'carbon_date_time';

    public function getName()
    {
        return static::CARBON_DATE_TIME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Carbon::instance(parent::convertToPHPValue($value, $platform));
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
