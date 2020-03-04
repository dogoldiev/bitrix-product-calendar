<?php
namespace Calendar;

use Bitrix\Main\Type\DateTime as BitrixDateTime;

class ProductionCalendar
{
    const FORMAT_DMY = 'd.m.Y';

    /**
     * Праздники
     * @var array
     */
    private static $holidays;

    /**
     * Рабочие выходные
     * @var array
     */
    private static $workingWeekend;


    /**
     * Не рабочий день,  Например 24 февраля 2019 года
     * @var array
     */
    private static $additionalWeekends;


    /**
     * @var BitrixDateTime
     */
    private $date;

    public function __construct($dateTimestamp)
    {
        $this->date = BitrixDateTime::createFromTimestamp($dateTimestamp);
        if (!self::$holidays) {
            $calendar = json_decode(file_get_contents(__DIR__ . '/calendar/calendar.json'), true);
            self::$holidays = isset($calendar['holidays']) ? $calendar['holidays'] : [];
            self::$workingWeekend = isset($calendar['works']) ? $calendar['works'] : [];
            self::$additionalWeekends = isset($calendar['weekends']) ? $calendar['weekends'] : [];
        }
    }

    /**
     * Выходной?
     * @return bool
     */
    private function isWeekend()
    {
        return (bool) ($this->date->format("N") >= 6);
    }


    /**
     * Рабочий выходной
     * @return bool
     */
    private function isWorkingWeekend()
    {
        return (bool) in_array($this->date->format(self::FORMAT_DMY), self::$workingWeekend);
    }


    /**
     * Праздник
     * @return false|string
     */
    public function isHoliday()
    {
        return in_array($this->date->format(self::FORMAT_DMY), self::$holidays);
    }

    /**
     * Дополнительный выходной
     * @return bool
     */
    private function isAdditionalWeekend()
    {
        return (bool) in_array($this->date->format(self::FORMAT_DMY), self::$additionalWeekends);
    }

    /**
     * Рабочий день
     * @return bool
     */
    public function isWorkingDay()
    {
        if ($this->isWeekend() && !$this->isWorkingWeekend()) {
            return false;
        }
        if ($this->isHoliday()) {
            return false;
        }
        if ($this->isAdditionalWeekend()) {
            return false;
        }
        return true;
    }

    /**
     * Получаем следующий рабочий день
     * @return false|int
     */
    public function getNextWorkingDay()
    {
        $nextDate = clone $this->date;
        while (true) {
            $nextDate = $nextDate->add('+1 days');
            $time = $nextDate->getTimestamp();
            $date = new self($time);
            if (!$date->isWorkingDay()) {
                continue;
            }
            return $time;
        }
    }

    /**
     * Получаем предыдущий рабочий день
     * @return false|int
     */
    public function getPreviousWorkingDay()
    {
        $prevDate = clone $this->date;
        while (true) {
            $prevDate = $prevDate->add('-1 days');
            $time = $prevDate->getTimestamp();
            $date = new self($time);
            if (!$date->isWorkingDay()) {
                continue;
            }
            return $time;
        }
    }

    /**
     * @param $countDay
     * @return mixed
     */
    public function missNextWorkingDay($countDay)
    {
        $time = $this->getNextWorkingDay();
        for ($i = 1; $i <$countDay; $i++) {
            $dateTime = new self($time);
            $time = $dateTime->getNextWorkingDay();
        }
        return BitrixDateTime::createFromTimestamp($time);
    }

}