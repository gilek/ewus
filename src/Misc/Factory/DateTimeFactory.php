<?php
declare(strict_types=0);

namespace Gilek\Ewus\Misc\Factory;

use DateTimeImmutable;
use Gilek\Ewus\Misc\Exception\InvalidDateException;

class DateTimeFactory
{
    /**
     * @param string $dateTime
     *
     * @return DateTimeImmutable
     *
     * @throws InvalidDateException
     */
    public function createDateTime(string $dateTime): DateTimeImmutable
    {
        $dateObject = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.vO', $dateTime);
        if ($dateObject === false || $this->hasDateTimeReportedErrors()) {
            throw new InvalidDateException(sprintf('Can\'t create date from "%s".', $dateTime));
        }

        return $dateObject;
    }

    /**
     * @param string $date
     *
     * @return DateTimeImmutable
     *
     * @throws InvalidDateException
     */
    public function createDate(string $date): DateTimeImmutable
    {
        $dateObject = DateTimeImmutable::createFromFormat('!Y-m-dO', $date);
        if ($dateObject === false || $this->hasDateTimeReportedErrors()) {
            throw new InvalidDateException(sprintf('Can\'t create date from "%s".', $date));
        }

        return $dateObject;
    }

    /**
     * @return bool
     */
    private function hasDateTimeReportedErrors(): bool
    {
        $errors = DateTimeImmutable::getLastErrors();

        return $errors['warning_count'] > 0 || $errors['error_count'] > 0;
    }
}
