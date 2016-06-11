<?php
    /*
     * ValveWhitelist utility.
     * This class can be used to verify incoming http requests from 'clients'. It verifies the
     * IP against a list of legit valve servers based on regions.txt to prevent fake data.
     * 
     * Example usage:
     *
     * require_once("valveWhitelist.php");
     * if (ValveWhiteList::verifyIp($_SERVER["REMOTE_ADDR"]) == true) {
     *      // Legit request, do something...
     * }
     */
    require_once("valveKV.php");

    class ValveWhitelist {

        const FILE_NAME = "whitelist.json"; // The file name for the cache
        const REGIONS_URL = "https://raw.githubusercontent.com/SteamDatabase/GameTracking/master/dota/game/dota/pak01_dir/scripts/regions.txt"; // The URL with an up to date regions.txt
        const UPDATE_INTERVAL = 0; // Cache file lifetime (in seconds).

        private static $ipList;

        // Check if an ip (string) is in the whitelist
        public static function verifyIp($str) {
            $split = explode(".", $str);

            $ip = intval($split[0]) << 24
                | intval($split[1]) << 16
                | intval($split[2]) << 8
                | intval($split[3]);

            foreach( self::$ipList as $k => $range ) {
                if ($ip >= $range[0] && $ip <= $range[1]) {
                    return true;
                }
            }

            return false;
        }

        // Initialise the whitelist
        public static function init() {
            if (file_exists(self::FILE_NAME)) {
                $fileStr = file_get_contents(self::FILE_NAME);
                $fileObj = json_decode($fileStr);

                $updateTime = $fileObj[0];
                if (time() - $updateTime > self::UPDATE_INTERVAL) {
                    self::reload();
                } else {
                    self::$ipList = $fileObj[1];
                }
            } else {
                self::reload();
            }
        }

        // Reload regions.txt from the correct url
        private static function reload() {
            $remoteList = file_get_contents(self::REGIONS_URL);
            
            $kvParser = new ValveKV($remoteList );
            $regionsObj = $kvParser->parse();

            $list = array();

            foreach($regionsObj as $regionName => $region ) {
                if (is_array($region["ip_range"])) {
                    foreach($region["ip_range"] as $i => $ipStr ) {
                        $list[] = self::parseIp( $ipStr );
                    }
                } else {
                    $list[] = self::parseIp($region["ip_range"]);
                }
            }

            self::$ipList = $list;

            file_put_contents(self::FILE_NAME, json_encode(array(time(), self::$ipList)));
        }

        // Parse a string to an IP range object
        private static function parseIp($str) {
            if (strpos($str, '/') !== false) {
                $split1 = explode( "/", $str );
                $split2 = explode( ".", $split1[0] );

                $ip = intval($split2[0]) << 24
                    | intval($split2[1]) << 16
                    | intval($split2[2]) << 8
                    | intval($split2[3]);

                $shift = 32 - intval($split1[1]);
                $min = $max = $ip;

                if ($shift > 0) {
                    $min = ($ip >> $shift) << $shift;
                    $max = $ip | ((1 << $shift) - 1);
                }

                return array($min, $max);
            } else {

            }
        }
    }

    // Initialise whitelist
    ValveWhitelist::init();
?>