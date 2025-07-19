<?php

function gregorian_to_jalali($gy, $gm, $gd)
{
    // Number of days in months in the Gregorian calendar
    $g_d_m = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    if ($gy > 1600) {
        $jy = 979;
        $gy -= 1600;
    } else {
        $jy = 0;
        $gy -= 621;
    }
    $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
    $days = (365 * $gy) + (((int)($gy2 + 3) / 4)) - (((int)($gy2 + 99) / 100)) + (((int)($gy2 + 399) / 400)) - 80 + $gd + array_sum(array_slice($g_d_m, 0, $gm));
    $jy += 33 * ((int)($days / 12053));
    $days %= 12053;
    $jy += 4 * ((int)($days / 1461));
    $days %= 1461;
    if ($days > 365) {
        $jy += (int)(($days - 1) / 365);
        $days = ($days - 1) % 365;
    }
    $jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
    $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
    return array($jy, $jm, $jd);
}

function getDayName($dayNumber) {
    $days = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
    return $days[$dayNumber];
}

function getFormattedJalaliDate($gregorianDate, $time = null) {
    list($gy, $gm, $gd) = explode('-', $gregorianDate);
    $jalaliDate = gregorian_to_jalali($gy, $gm, $gd);
    list($jy, $jm, $jd) = $jalaliDate;

    // Specify the name of the day of the week
    $dayOfWeek = date('w', strtotime($gregorianDate));

    date_default_timezone_set('Asia/Tehran');
    
    // time
    if ($time === null) {
        $time = date('H:i');
    }

    // Output creation
    $dayName = getDayName($dayOfWeek);
    $formattedDate = "📅 " . $dayName . " " . str_pad($jd, 2, '0', STR_PAD_LEFT) . " " . getMonthName($jm) . " " . $jy . " - " . $time;
    return $formattedDate;
}

function getMonthName($month) {
    $months = [
        1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور',
        7 => 'مهر', 8 => 'آبان', 9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
    ];
    return $months[$month];
}

?>
