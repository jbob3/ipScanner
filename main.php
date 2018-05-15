<?php
include("bin/ipInc.php");

//Setup some vars
$ip_range[] = array("start" => "10.80.70.1", "end" => "10.80.70.50");
$ip_range[] = array("start" => "10.80.214.1","end" => "10.80.214.54");

$time = time();
$out_array = array();

$ip_count = 0;
$count_ips_to_scan = 0;
$count_sub_range = 0;
echo "Counting IPs to scan... Please Wait : ";

//Do some counting
foreach($ip_range as $range){
    $start = $range['start'];
    $c_ip = $start;
    $end = $range['end'];

    echo "S: " . $start . " E: " . $end . "\n";

    do{
        $count_ips_to_scan++;
        $count_sub_range++;

        $c_ip = inc_ip($c_ip);

        if($c_ip == FALSE){
            echo "STOP";
            break;
        }
        
    }
    while(ip2long($c_ip) != ip2long($end));

}
echo "Total ips: " . $count_ips_to_scan . "\n";

//Let's start scanning
$count_scanned = 0;

foreach ($ip_range as $range) {
    $start = $range['start'];
    $c_ip = $start;
    $end = $range['end'];
    $up = false;

    do{
        list($usec, $sec) = explode(" ", microtime());
        $time_start = ((float)$usec + (float)$sec);

        $outString = NULL;
        $count_scanned++;

        echo "Scanned " . $count_scanned . " out of " . $count_ips_to_scan . "IPs \n";
        echo "Scanning: " . $c_ip . "\n";

        require_once('Net/Ping.php');
        $ping = Net_Ping::factory();

        if(PEAR::isError($ping)){
            echo "ERROR:: " . $ping->getMessage();
        }
        else{
            $t_c_ip = explode(".", $c_ip);

            if(($t_c_ip[3] > 0) && ($t_c_ip[3] < 255)){
                $ping->setArgs(array('count' => 1, 'timeout' => 3));
                $raw_data = $ping->ping($c_ip);
                //print_r($raw_data);

                if($raw_data->getValue("_transmitted") == $raw_data->getValue("_received")){
                    echo "Host is up!\n";
                }
                else{
                    echo "Host is down! Running full scan!\n";
                }
            }
        }
        $c_ip = inc_ip($c_ip);
        if($c_ip == FALSE){
            echo "STOP:";
            break;
        }
    }
    while(ip2long($c_ip) != ip2long($end) + 1);
}

?>