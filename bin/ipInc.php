<?php

function inc_ip($ip)
{
    $ip_array = explode(".", $ip);

    $last_octet = (int)$ip_array[3];
    
    if ($last_octet < 255) {
    
        $last_octet++;

        $last_octet = (string)$last_octet;
        $ip_array[3] = $last_octet;

        $ipNew = implode(".",$ip_array);
        return $ipNew;
    }
    else{
        return false;
    }   
    
}

?>