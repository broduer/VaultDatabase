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

function secondsToDate($seconds, $timezone, $timestamp)
{
  $date = new DateTime();
  if ($timezone != "null") {
      $date->setTimezone(new DateTimeZone($timezone));
  } else {
      $date->setTimezone(new DateTimeZone("America/Vancouver"));
  }
  $date->setTimeStamp($seconds);
  if ($timestamp == true){
    return $date->format("M jS Y h:ia");
  }
  else {
    return $date->format("M jS Y");
  }
}

function listTimezones()
{
    $zones_array = array();
    $timestamp = time();
    foreach(timezone_identifiers_list() as $key => $zone) {
      date_default_timezone_set($zone);
      $zones_array[$key]['zone'] = $zone;
      $zones_array[$key]['offset'] = (int) ((int) date('O', $timestamp))/100;
      $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
    }
    return $zones_array;
}
/* mysql login */
$player_data = "mysql:host=localhost;dbname=VaultMC_Data";
$punishment_data = "mysql:host=localhost;dbname=VaultMC_Punishments";
$clan_data = "mysql:host=localhost;dbname=VaultMC_Clans";
$username = "tadhg";
$password = "Stjames123b!";

// need to return an array so that when called, we can modify data however we want...
function pdoQueryStatement($database, $select, $from, $where, $is, $target, $extra) {

  global $username;
  global $password;

  $query = "SELECT " . $select . " FROM " . $from . " WHERE " . $where . " " . $is . " '" . $target . "' " . $extra . "";
  echo $query;
  $pdo = new PDO($database, $username, $password);
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $selection = explode(", ", $select);

  if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      foreach ($selection as $sel) {
        echo $row[$sel];
        echo "<br>";
      }
    }
  } else {
    print_r($stmt->errorInfo());
  }
  $pdo = null;
}

// can only set one value at a time right now
function pdoUpdateStatement($database, $update, $set, $to, $where, $is)
{
  global $username;
  global $password;

  $query = "UPDATE " . $update . " SET " . $set . " = '" . $to . "' WHERE " . $where . " = '" . $is . "'";
  echo $query;
  $pdo = new PDO($database, $username, $password);
  $stmt = $pdo->prepare($query);

  if (!$stmt->execute()) {
    print_r($stmt->errorInfo());
  }
  $pdo = null;
}
?>
