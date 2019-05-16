<?php
/**
 * Class DayCounter
 * @version 0.2.1
 * @license MIT
 * @copyright vlsv 18.11.2018
 * @author Evgeny Vlasov mail@vlsv.me
 */
class DayCounter
{
    /**
     * @static
     * @param $d - day [dd]
     * @param $m - month [mm]
     * @param $y - year [yyyy]
     * @return string
     */
    public static function counter($d, $m, $y)
    {
        if ($d > 31 or $d < 1 or $m > 12 or $m < 1 or $y > 9999 or $y < 200) {
            return 'Ошибка в дате! 
Дату необходимо указывать в формате ДД.ММ.ГГГГ 
Например: 31.12.2018';
            exit;
        }

        $r = 0;

        $nowDate = time(); // Текущая дата
        $specifiedDate = mktime(0, 0, 0, $m, $d, $y); // Указываемая дата

        if ($specifiedDate < $nowDate) {
            for ($time = $specifiedDate, $month = 0;
                 $time < $nowDate;
                 $time = $time + date('t', $time) * 86400, $month++) {
                $rtime = $time;
            }
            $corr = 0; // Корректор дней
            $rel[] = 'прошло';
            $rel[] = 'прошел';
            $pre = 'С';
        } else {
            for ($time = $specifiedDate, $month = 0;
                 $time > $nowDate;
                 $time = $time - date('t', $time) * 86400, $month++) {
                $rtime = $time;
            }
            $corr = 1; // Корректор дней
            $rel[] = 'осталось';
            $rel[] = 'остался';
            $pre = 'До';
        }

        $month = $month - 1;
        $year = (int)($month / 12); // Количество лет
        $month = $month % 12; // Количество месяцев
        $day = abs((int)(($nowDate - $rtime) / 86400)) + $corr; // Количество дней

        if ($year) {
            $andM = $andD = 'и ';
            $result = $this->declination($year, "год", "года", "лет") . " ";
        }
        if ($month) {
            if ($andM) {
                $andM = '';
            }
            $andD = 'и ';
            $result .= $andM . $this->declination($month, "месяц", "месяца", "месяцев") . " ";
        }
        if ($day) {
            $result .= $andD . $this->declination($day, "день", "дня", "дней") . " ";
        }
        if (!$year and !$month and !$day) {
            $rel = $rel = '';
            $result = 'Это сегодня!';
        }

        // Замена чисел месяца на текст
        $monthsNum = ['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'];
        $monthsText = [
            'декабря',
            'ноября',
            'октября',
            'сентября',
            'августа',
            'июля',
            'июня',
            'мая',
            'апреля',
            'марта',
            'февраля',
            'января',
        ];
        $mc = str_replace($monthsNum, $monthsText, $m);

        if ($rel != '') {
            $preDate = $pre . ' ' . $d . ' ' . $mc . ' ' . $y . ' года';
        }

        if (preg_match("|[1]$|", $day) && $month == '0' && $year == '0' && $day != '11') {
            $r = 1;
        }

        if (preg_match("|[1]$|", $month) && $year == '0' && $month != '11') {
            $r = 1;
        }

        if (preg_match("|[1]$|", $year) && $year != '11' && $year != '111' && $year != '1111') {
            $r = 1;
        }

        return $preDate . ' ' . $rel[$r] . ' ' . $result;
    }

    // Склонение числа
    function declination($num, $one, $ed, $mn, $notnumber = false)
    {
        if ($num === "") {
            print "";
        }
        if (($num == "0") or (($num >= "5") and ($num <= "20")) or preg_match("|[056789]$|", $num)) {
            if (!$notnumber) {
                return "$num $mn";
            } else {
                return $mn;
            }
        }
        if (preg_match("|[1]$|", $num)) {
            if (!$notnumber) {
                return "$num $one";
            } else {
                return $one;
            }
        }
        if (preg_match("|[234]$|", $num)) {
            if (!$notnumber) {
                return "$num $ed";
            } else {
                return $ed;
            }
        }
    }
}