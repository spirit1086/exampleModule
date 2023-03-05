<?php

namespace App\Helpers\Dates;

class Dates {

    CONST MONTHS = [
        '01' => 'января',
        '02' => 'февраля',
        '03' => 'марта',
        '04' => 'апреля',
        '05' => 'мая',
        '06' => 'июня',
        '07' => 'июля',
        '08' => 'августа',
        '09' => 'сентября',
        '10' => 'октября',
        '11' => 'ноября',
        '12' => 'декабря',
    ];

    CONST MONTHSImp = [
        '01' => 'январь',
        '02' => 'февраль',
        '03' => 'март',
        '04' => 'апрель',
        '05' => 'май',
        '06' => 'июнь',
        '07' => 'июль',
        '08' => 'август',
        '09' => 'сентябрь',
        '10' => 'октябрь',
        '11' => 'ноябрь',
        '12' => 'декабрь',
    ];

    public static function differenceYear(string $startDate, string $endDate)
    {
        $datetime1 = new \DateTime($startDate);
        $datetime2 = new \DateTime($endDate);
        $interval = $datetime1->diff($datetime2);
        return (int)$interval->format('%y');
    }

    public static function differenceMonths(string $startDate, string $endDate)
    {
        $datetime1 = new \DateTime($startDate);
        $datetime2 = new \DateTime($endDate);
        $interval = $datetime1->diff($datetime2);
        $yearsInMonths = $interval->format('%r%y') * 12;
        $months = $interval->format('%r%m');
        $totalMonths = $yearsInMonths + $months;
        return (int)$totalMonths;
    }

    public static function expirienceJob(int $countMonths)
    {
        $year = floor($countMonths/12);
        $offsetMonths = $countMonths - $year * 12;
        $months = self::declOfNum($offsetMonths, array('месяц', 'месяца', 'месяцев'));
        return self::declOfNum($year, array('год', 'года', 'лет')) . ' ' .$months;
    }

    public static function declOfNum($num, $titles) {
        $cases = array(2, 0, 1, 1, 1, 2);
        return $num . " " . $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
    }
}
