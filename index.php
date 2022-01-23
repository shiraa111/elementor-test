<?php
session_start();
if(!isset($_SESSION["connected"]))
{
    header("location: login.php");
}
else {

    //Goes through the entire list of connected and checks if they are their status is correct.
    $str = file_get_contents(__DIR__ . '/user_details.json');
    $json = json_decode($str, true);
    foreach ($json as $key => $row){
        if ($row['connected'] == 'online'){

            $datetime1 = strtotime(date("d-m-y H:i:s"));

            $datetime2 = strtotime("+6 sec", strtotime($json[$key]['last_update_time']));

            if ($datetime1 < $datetime2) {
                $json[$key]['connected'] = 'offline';
            }
        }
    }

        $newJsonString = json_encode($json);
        file_put_contents(__DIR__ .'/user_details.json', $newJsonString);

}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Display Users Online</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<br />
<div class="container">
    <div align="center"><h2>Welcome  <?php echo $_SESSION["user_name"]?>. </h2></div>
    <br />
    <div align="right">
        <a href="logout.php">Logout</a>
    </div>
    <br />
        <div class="panel panel-default">
            <div class="panel-heading">Online User Details</div>
            <div id="user_login_status" class="panel-body">

            </div>
        </div>


    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>


</div>
</body>
</html>

<script>
    $(document).ready(function(){
        <?php
        if($_SESSION["connected"] == "online")
        {
        ?>
        fetch_user_login_data();
        setInterval(function(){
            fetch_user_login_data();
        }, 3000);
        function fetch_user_login_data()
        {
            var action = "fetch_data";
            $.ajax({
                url:"action.php",
                method:"POST",
                data:{action:action},
                success:function(data)
                {
                    $('#user_login_status').html(data);
                }
            });
        }
        <?php
        }
        ?>

        $('#user_login_status').on('click', 'table tr', function() {
            var action = "user_details";
            var user_email = $(this).attr('id');
            $.ajax({
                url:"action.php",
                method:"POST",
                data:{action:action, user_email:user_email },
                success:function(data)
                {
                    $('.modal-body').html(data);
                    $("#myModal").modal('show');

                }
            });
        });
    });

</script>
