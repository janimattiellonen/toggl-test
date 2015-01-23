<?php

function getWorkingDays($startDate, $endDate)
{
    $begin = strtotime($startDate);
    $end = strtotime($endDate);
    if ($begin > $end) {
        echo "startdate is in the future! <br />";
        return 0;
    } else {
        $no_days = 0;
        $weekends = 0;

        while ($begin <= $end) {
            $no_days++; // no of days in the given interval
            $what_day = date("N",$begin);

            if ($what_day > 5) { // 6 and 7 are weekend days
                $weekends++;
            };

            $begin += 86400; // +1 day
        };

        $working_days = $no_days - $weekends;
        return $working_days;
    }
}



function get_weekdays($m, $y)
{
    $lastday = date("t", mktime(0, 0, 0, $m, 1, $y));
    $weekdays = 0;

    for ($d = 1; $d <= $lastday; $d++) {
        $wd = date("w", mktime(0, 0, 0, $m, $d, $y));
        if ($wd > 0 && $wd < 6) $weekdays++;
    }

    return $weekdays;
}