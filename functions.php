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
    'day' => (int) $days,
    'hour' => (int) $hours,
    'minute' => (int) $minutes,
    'second' => (int) $seconds,
  ];
  foreach ($sections as $name => $value) {
    if ($value > 0) {
      $timeParts[] = $value . ' ' . $name . ($value == 1 ? '' : 's');
    }
  }
  return implode(', ', $timeParts);
}

/**
 * @author Tadhg Boyle
 * @return string Date with timezone applied
 */
function secondsToDate($seconds, $timezone, $timestamp)
{
  $date = new DateTime();
  if ($timezone != "null") {
    $date->setTimezone(new DateTimeZone($timezone));
  } else {
    $date->setTimezone(new DateTimeZone("America/Vancouver"));
  }
  $date->setTimeStamp($seconds);
  if ($timestamp == true) {
    return $date->format("M jS Y h:ia");
  } else {
    return $date->format("M jS Y");
  }
}

/**
 * @author Tadhg Boyle
 * @return array Array of all php timezones
 */
function timezoneListSimple()
{
  $zones_array = array();
  foreach (timezone_identifiers_list() as $key => $zone) {
    date_default_timezone_set($zone);
    $zones_array[$key] = $zone;
  }
  return $zones_array;
}

function listTimezones()
{
  $zones_array = array();
  $timestamp = time();
  foreach (timezone_identifiers_list() as $key => $zone) {
    date_default_timezone_set($zone);
    $zones_array[$key]['zone'] = $zone;
    $zones_array[$key]['offset'] = (int) ((int) date('O', $timestamp)) / 100;
    $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
  }
  return $zones_array;
}

/**
 * @author Tadhg Boyle
 * @return string Full url from when it was called
 */
function currentUrl()
{
  $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === FALSE ? 'http' : 'https';
  $host = $_SERVER['HTTP_HOST'];
  $script = $_SERVER['SCRIPT_NAME'];
  $params = $_SERVER['QUERY_STRING'];

  return $protocol . '://' . $host . $script . '?' . $params;
}

/**
 * @author Tadhg Boyle
 * @return string URL without the param
 */
function stripUrlParam($url, $parameter)
{
  if (substr($url, 0, strpos($url, "&" . $parameter)) == null) {
    return $url;
  } else {
    return substr($url, 0, strpos($url, "&" . $parameter));
  }
}

function readableFilesize($bytes, $decimals = 2)
{
  $size = array('B', 'kB', 'MB');
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}