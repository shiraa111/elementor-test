<?php
//action.php
if(isset($_POST["action"]))
{
    if($_POST["action"] == "fetch_data")
    {
        $output1 = '';
        $output2 = '';
        $str = file_get_contents(__DIR__ .'/user_details.json');
        $json = json_decode($str, true);
        $time = date("d-m-y H:i:s");


        $i = 0;
        foreach($json as $key => $row)
        {
            if ($row['connected'] == 'online'){
            $i = $i + 1;
            $output2 .= '
               <tr id="'.$key.'" class="user_details"> 
                <td>'.$i.'</td>
                <td>'.$row["user_name"].'</td>
                <td>'.$row["entrance_time"].'</td>
                <td>'.$time.'</td>
                <td>'.$row["user_ip"].'</td>
               </tr>
               ';
                $json[$key]['last_update_time'] = $time;

            }
        }
        $output2 .= '</table></div>';

        $output1 .= '
      <div id="fetch_data" class="table-responsive">
       <div align="right">
        '.$i.' Users Online
       </div>
       <table class="table table-bordered table-striped">
        <tr>
         <th>No.</th>
         <th>Name</th>
         <th>Entrance time</th>
         <th>Last update time</th>
         <th>User IP</th>
        </tr>
      ';


        $newJsonString = json_encode($json);
        file_put_contents(__DIR__ .'/user_details.json', $newJsonString);

        echo $output1.$output2;
    }
    if($_POST["action"] == "user_details")
    {
        $output1 = '';
        $str = file_get_contents(__DIR__ .'/user_details.json');
        $json = json_decode($str, true);
        $user_email = $_POST["user_email"];
        $user_details = $json[$user_email];

        $output = '';

        $output .= '
      <div class="table-responsive">
       <table class="table table-bordered table-striped">
        <tr>
         <th>Name</th>
         <th>Email</th>
         <th>User-Agent</th>
         <th>Entrance time</th>
         <th>Visits count</th>
        </tr>
        <tr id="'.$user_email.'" class="user_details"> 
                <td>'.$user_details["user_name"].'</td>
                <td>'.$user_email.'</td>
                <td>'.$user_details["user_agent"].'</td>
                <td>'.$user_details["entrance_time"].'</td>
                <td>'.$user_details["visits_count"].'</td>
               </tr>
        
      </table></div>';
        echo $output;
    }
}
?>
