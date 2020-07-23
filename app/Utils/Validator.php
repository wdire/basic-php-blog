<?php 

    namespace app\Utils;

    class Validator{
        private static $passRegx = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).*$/";
        private static $unameRegx = "/^([a-zA-ZÇŞĞÜÖİçşğüöı0-9-_\.@]+)$/";

        public static function size(string $value, int $min, int $max){
            return (strlen($value) >= $min && strlen($value) < $max);
        }

        public static function email($value){
            return filter_var($value, FILTER_VALIDATE_EMAIL) ? true : false;
        }

        public static function username($value){
            return preg_match(self::$unameRegx, $value) ? true : false;
        }

        public static function password($value){
            return preg_match(self::$passRegx, $value) ? true : false;
        }

        public static function arrKeysEqual($arr1, $arr2){
            return(!array_diff_key($arr1,$arr2) && !array_diff_key($arr1,$arr2));
        }

        public static function hasSpace($value){
            return strpos($value, " ");
        }
    }

?>