<?php
session_start();
if(isset($_SESSION["connected"]))
{
    $str = file_get_contents(__DIR__ .'/user_details.json');
    $json = json_decode($str, true);
    $user_email = $_SESSION["user_email"];
    if(isset($json[$user_email])) {
        $json[$user_email]['connected'] = 'offline';
        $newJsonString = json_encode($json);
        file_put_contents(__DIR__ .'/user_details.json', $newJsonString);
    }
}
session_destroy();
header("location:login.php");
?>
