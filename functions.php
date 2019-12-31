<?php
function secondsToTime($time_in_seconds)
{
    $secondsInAMinute = 60;
    $secondsInAnHour = 60 * $secondsInAMinute;
    $secondsInADay = 24 * $secondsInAnHour;
    $days = floor($time_in_seconds / $secondsInADay);
    $hourSeconds = $time_in_seconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);
    $timeParts = [];
    $sections = [
        'day' => (int)$days,
        'hour' => (int)$hours,
        'minute' => (int)$minutes,
        'second' => (int)$seconds,
    ];
    foreach ($sections as $name => $value) {
        if ($value > 0) {
            $timeParts[] = $value . ' ' . $name . ($value == 1 ? '' : 's');
        }
    }
    return implode(', ', $timeParts);
}

function secondsToLongTime($sec)
{
  $date1 = new DateTime("@0");
  $date2 = new DateTime("@$sec");
  $interval =  date_diff($date1, $date2);
  return $interval->format('%y year, %m months, %d days, %h hours, %i minutes and %s seconds');
}
?>
