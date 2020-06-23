<?php
if (!isset($_SESSION)) session_start();
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "config.php";
$_SESSION['next'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if ($result = $mysqli_d->query('SELECT reddit_token FROM players WHERE uuid = "' . $_SESSION['uuid'] . '"')) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['reddit_token'] != null) {
                    die('You have already linked your Reddit and Minecraft accounts!');
                }
            }
        }
    } else die('Error: Could not check if you have already linked your Reddit and Minecraft accounts.');
    if (isset($_GET['code']) && isset($_GET['state'])) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.reddit.com/api/v1/access_token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['grant_type' => 'authorization_code', 'code' => $_GET['code'], 'redirect_uri' => 'https://vaultmc.net/reddit.php']));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . base64_encode($client_info_reddit)]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if (array_key_exists('error', $response)) die('Error: ' . $response['error']);
        else if (array_key_exists('access_token', $response) && array_key_exists('refresh_token', $response) && array_key_exists('expires_in', $response)) {
            $keyVal = ['reddit_token' => $response['access_token'], 'reddit_refresh_token' => $response['refresh_token'], 'reddit_expiration' => $response['expires_in'], 'reddit_new_link' => true];
            foreach ($keyVal as $key => $value)  if (!insertToDatabase($mysqli_d, $_SESSION['uuid'], $key, $value)) die('Error: Could not update our database for ' . $key . ' -> ' . $value . '. Please contact an Administrator.');
            header('Location: https://vaultmc.net/?page=home&alert=linked_reddit');
        } else die('Error: access_token and/or refresh_token and/or expires_in were not provided by Reddit\'s API. Please contact an Administrator.');
    } else die('You have not authorized through Reddit. Try running /reddit again in-game.');
} else header('Location: https://vaultmc.net/login.php');

function insertToDatabase($mysqli, $uuid, $key, $value): bool
{
    if ($mysqli->query('UPDATE players SET ' . $key . ' = \'' . htmlspecialchars($value) . '\' WHERE uuid = \'' . $uuid . '\'') !== true) return false;
    else return true;
}
