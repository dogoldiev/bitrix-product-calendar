<?php

use Bitrix\Main\Type\DateTime;


require 'productioncalendar.php';

$date = new \DateTime('2020-05-01');
$timestampFirstMay = $date->getTimestamp();

$productionCalendar = new \Calendar\ProductionCalendar($timestampFirstMay);
$productionCalendar->isHoliday();
$productionCalendar->getNextWorkingDay();