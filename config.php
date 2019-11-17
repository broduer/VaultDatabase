<?php
$server = "127.0.0.1";
$user = "tadhg";
$password = "Stjames123b!";
$db_punish = "VaultMC_Punishments";
$db_clans = "VaultMC_Clans";
$db_data = "VaultMC_Data";

$mysqli_p = new mysqli($server, $user, $password, $db_punish);
$mysqli_c = new mysqli($server, $user, $password, $db_clans);
$mysqli_d = new mysqli($server, $user, $password, $db_data);
?>
