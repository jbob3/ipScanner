<?php

$ip_range[] = array("start" => "10.80.70.1", "end" => "10.80.70.50");
$ip_range[] = array("start" => "10.80.214.1","end" => "10.80.214.54");

function ping($ip){
    require_once("Net/Ping.php");
    $ping = Net_Ping::factory();
    $ping->setArgs(array('count' => 1, 'timeout' => 3));
    $raw_data = $ping->ping($c_ip);
    if($raw_data->getValue("_transmitted") == $raw_data->getValue("_received")){
        echo "Host " . $c_ip . " is up!\n";
    }
    else{
        echo "Host " . $c_ip . " is down!\n";
    }

}


foreach($ip_range as $range){
    $start = $range['start'];
    $c_ip = $start;
    $end = $range['end'];

    do{
        
        $pid = pcntl_fork();
        if ($pid == -1) {
            exit("Error forking...\n");
        }
        else if ($pid == 0) {
            ping($c_ip);
        }
        $c_ip = inc_ip($c_ip);
        while(pcntl_waitpid(0, $status) != -1);
    }
   while(ip2long($c_ip) != ip2long($end) + 1);
}


?>