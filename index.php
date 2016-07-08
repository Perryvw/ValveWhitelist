<?php
    // Require library
    require_once("valveWhitelist.php");

    // Example usage
    /*
        if (ValveWhitelist::verifyIp($_SERVER["REMOTE_ADDR"]) == true) {
            // Legit request
            // Insert data into database
        }
    */

    // Test cases:
    $tests = array(
        //USWest - 208.64.201.0/24
        "208.64.201.0" => true,
        "208.64.201.255" => true,
        "208.64.202.0" => false,
        "208.64.200.255" => false,

        //USWest - 192.69.96.0/22
        "192.69.96.0" => true,
        "192.69.99.255" => true,
        "192.69.95.255" => false,
        "192.69.100.0" => false,

        //SouthAfrica - 196.38.180.17-196.38.180.22
        "196.38.180.16" => false,
        "196.38.180.17" => true,
        "196.38.180.20" => true,
        "196.38.180.22" => true,
        "196.38.180.23" => false
    );
    
    // Run tests
    echo "Testcases:<br>";
    foreach($tests as $ip => $expect) {
        $action = $expect ? "verified" : "denied";
        if (ValveWhitelist::verifyIp($ip) == $expect) {
            echo "<span style='color: #00cc00'>".$ip." succesfully <b>".$action."</b></span><br>";
        } else {
            echo "<span style='color: #cc0000'>".$ip." incorrectly <b>".$action."</b></span><br>";
        }
    }
?>