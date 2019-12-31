<?php
function secondsToTime($seconds)
{
    $secondsInAMinute = 60;
    $secondsInAnHour = 60 * $secondsInAMinute;
    $secondsInADay = 24 * $secondsInAnHour;
    $days = floor($seconds / $secondsInADay);
    $hourSeconds = $seconds % $secondsInADay;
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

function secondsToLongTime($seconds)
{
  $date1 = new DateTime("@0");
  $date2 = new DateTime("@$seconds");
  $interval =  date_diff($date1, $date2);
  return $interval->format('%y year, %m months, %d days, %h hours, %i minutes and %s seconds');
}

function secondsToDate($seconds, $timezone)
{
  $date = new DateTime();
  if ($timezone != "null") {
      $date->setTimezone(new DateTimeZone($timezone));
  } else {
      $date->setTimezone(new DateTimeZone("America/Vancouver"));
  }
  $date->setTimeStamp($seconds);
  echo $date->format("M jS Y h:ia");
}
?>
