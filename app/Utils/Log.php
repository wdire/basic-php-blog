<?php 

    namespace app\Utils;

    class Log{

        private static $logLocation = __PATH__."/app/errors.log";
        private static $dateFormat = "[Y-m-d H:i:s.u]";

        public static function do($message = ""){
            if(!empty($message)){
                $date = new \DateTime();
                $text = $date->format(self::$dateFormat) . " - " . $message."\n";
                error_log($text,3,self::$logLocation);
            }
            // Else Log the Logging Error :D
        }

    }

?>