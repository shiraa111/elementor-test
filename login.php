<?php
session_start();
if(isset($_SESSION["connected"]))
{
    header("location: index.php");
}
$message = '';

if(isset($_POST["login"]))
{
    if(empty($_POST["user_email"]) || empty($_POST["user_password"]))
    {
        $message = "<label>Both Fields are required</label>";
    }
    else
    {
        $str = file_get_contents(__DIR__ .'/user_details.json');
        $json = json_decode($str, true);
        $user_email = $_POST["user_email"];

        if(isset($json[$user_email]))
        {

                if($_POST["user_password"] == $json[$user_email]["user_password"])
                {

                    $json[$user_email]['entrance_time'] = date("d-m-y H:i:s");
                    $json[$user_email]['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                    $json[$user_email]['visits_count'] = $json[$user_email]['visits_count']+1;
                    $json[$user_email]['user_ip'] = get_client_ip();
                    $json[$user_email]['connected'] = 'online';

                    $newJsonString = json_encode($json);
                    file_put_contents(__DIR__ .'/user_details.json', $newJsonString);
                    $_SESSION["connected"] = 'online';
                    $_SESSION["user_name"] = $json[$user_email]['user_name'];
                    $_SESSION["user_email"] = $user_email;
                    header("location: index.php");
                }
                else
                {
                    $message = "<label>Wrong Password</label>";
                }

        }
        else
        {
            $message = "<label>Wrong Email Address</labe>";
        }
    }
}
if(isset($_POST["register"]))
{
    if(empty($_POST["user_email"]) || empty($_POST["user_password"]) || empty($_POST["user_name"]) )
    {
        $message_register = '<span class="text-danger"><label>Fields are required</labe></span>';
    }
    else
    {
        $str = file_get_contents(__DIR__ .'/user_details.json');
        $json = json_decode($str, true);
        $user_email = $_POST["user_email"];

        if(!isset($json[$user_email]))
        {
            $json[$user_email]['user_name'] = $_POST["user_name"];
            $json[$user_email]['user_password'] = $_POST["user_password"];
            $json[$user_email]['entrance_time'] = "";
            $json[$user_email]['last_update_time'] = "";
            $json[$user_email]['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $json[$user_email]['visits_count'] = 0;
            $json[$user_email]['user_ip'] = get_client_ip();
            $json[$user_email]['connected'] = 'offline';

            $newJsonString = json_encode($json);
            file_put_contents(__DIR__ .'/user_details.json', $newJsonString);

            $message_register = '<span class="text-success"><label>User added successfully</labe></span>';


        }
        else
        {
            $message_register = '<span class="text-danger"><label>The user already exists</labe></span>';
        }
    }
}

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Live Users Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style>
    .vl {
        position: absolute;
        left: 50%;
        transform: translate(-50%);
        border: 2px solid #ddd;
        height: 71%;
        display: inline-block
    }

    /* text inside the vertical line */
    .vl-innertext {
        position: absolute;
        top: 50%;
        transform: translate(-50%, -50%);
        background-color: #f1f1f1;
        border: 1px solid #ccc;
        border-radius: 50%;
        padding: 8px 10px;
    }
</style>
<body>
<br />
<div class="container">
    <h2 align="center">Live Users Dashboard</h2>
    <br />
    <div class="panel panel-default" style="    overflow: hidden;">
        <div class="panel-heading" style="width: 50%; display: inline-block;">Login</div>
        <div class="panel-heading" style="width: 49.5%; display: inline-block;">Register</div>
        <div class="panel-body col" style="display: inline-block;width: 47%; float: left;">
            <form method="post">
                <span class="text-danger"><?php echo $message; ?></span>
                <div class="form-group">
                    <label>User Email</label>
                    <input type="text" name="user_email" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="user_password" class="form-control" />
                </div>
                <div class="form-group">
                    <input type="submit" name="login" value="Login" class="btn btn-info" />
                </div>
            </form>
        </div>
        <div class="vl">
            <span class="vl-innertext">or</span>
        </div>
        <div class="panel-body" style="display: inline-block;width: 47%; float: right;">
            <form method="post">
                <?php echo isset($message_register) ? $message_register :''; ?>
                <div class="form-group">
                    <label>User Email</label>
                    <input type="text" name="user_email" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="user_name" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="user_password" class="form-control" />
                </div>

                <div class="form-group">
                    <input type="submit" name="register" value="register" class="btn btn-info" />
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>