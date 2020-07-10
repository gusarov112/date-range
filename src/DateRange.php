<?php
declare(strict_types=1);

namespace Gusarov112\DateRange;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use InvalidArgumentException;
use JsonSerializable;

class DateRange implements JsonSerializable
{
    /**
     * @var DateTimeImmutable
     */
    private $dateFrom;
    /**
     * @var DateTimeImmutable
     */
    private $dateTo;

    public function __construct(DateTimeImmutable $dateFrom, DateTimeImmutable $dateTo)
    {
        if ($dateFrom > $dateTo) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid date range date from [%s] should not be greater than date to [%s]',
                    $dateFrom->format(DATE_ISO8601),
                    $dateTo->format(DATE_ISO8601)
                )
            );
        } elseif ($dateFrom === $dateTo) {
            throw new InvalidArgumentException('Date range cannot consist of two same objects');
        }
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function getDateFrom(): DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function getDateTo(): DateTimeImmutable
    {
        return $this->dateTo;
    }

    public function hasInRange(DateTimeImmutable $date): bool
    {
        return $this->dateTo >= $date && $date <= $this->dateFrom;
    }

    public function isIntersects(self $dateRange): bool
    {
        return $this->hasInRange($dateRange->dateFrom)
            || $this->hasInRange($dateRange->dateTo)
            || $this->isOverlaps($dateRange);
    }

    public function isContains(self $dateRange): bool
    {
        return $this->hasInRange($dateRange->dateFrom) && $this->hasInRange($dateRange->dateTo);
    }

    public function isOverlaps(self $dateRange): bool
    {
        return $dateRange->dateFrom < $this->dateFrom && $dateRange->dateTo > $this->dateTo;
    }

    public function getDatePeriod(DateInterval $interval, bool $excludeStartDate = false): DatePeriod
    {
        return new DatePeriod(
            $this->dateFrom,
            $interval,
            $this->dateTo,
            $excludeStartDate ? DatePeriod::EXCLUDE_START_DATE : 0
        );
    }

    public function jsonSerialize()
    {
        return [
            'dateFrom' => $this->dateFrom->format(DATE_W3C),
            'dateTo' => $this->dateTo->format(DATE_W3C),
        ];
    }
}
