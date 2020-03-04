# bitrix-product-calendar

Производственный календарь
==========================
Список праздников в соответствии с производственным календарём Российской Федерации  
В файле `calendar/calendar.json` храним струтуру со списком:
 1. праздников 
 2. выходных дней, например 9 марта 2020 года приходится на понедельник, но является нерабочим днем,
    так как 8 марта приходится на воскресенье
 3. рабочих выходных 

`isWorkingDay()`       - проверяет является ли дата рабочим днем.  
`isHoliday()`          - проверяет является ли дата праздничным днем  
`getNextWorkingDay()`  - вычисляет следующий рабочий день за датой
`getNextWorkingDay()`  - вычисляет следующий рабочий день за датой
`missNextWorkingDay($count)` - отчитывает от текущей даты `$count` рабочих дней и выдает дату 

```
$date = new \DateTime('2020-05-01');
$timestampFirstMay = $date->getTimestamp();

$productionCalendar = new \Calendar\ProductionCalendar($timestampFirstMay);
$productionCalendar->isHoliday();//true
$productionCalendar->getNextWorkingDay(); //06.05.2020 в таймстампе

```
