<?php 

    namespace app\Utils;
    use app\Models\UserModel;

    class Auth{

        private static $userTypes = [
            "user"=>1,
            "author"=>2,
            "admin"=>3
        ];

        /**
         * Permssions:
         *  user:
         *      - Only access main pages
         *  author:
         *      - Can't access all articles
         *      - Can create article, can edit, delete OWN created articles
         *      - His/her articles needed to be published by admin to be.. published.
         *  admin:
         *      - Can do whatever the f*** he/she wants
         */

        /**
         * @param string $type see $userTypes constants
         */
        public static function checkPermission(String $type = "user"){
            $model = new UserModel();
            if($userLevel = $model->getCurrUserLevel()){
                if(self::$userTypes[$userLevel] >= self::$userTypes[$type]){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public static function redirectFailPermission(String $type = "user", $where = "../"){
            if(!self::checkPermission($type)){
                header("Location:".$where);
            }
        }

        public static function createToken($key = "token"){
            $t = bin2hex(openssl_random_pseudo_bytes(16));
            Session::set($key,$t);
            return $t;
        }

        public static function getToken($key = "token"){
            if(Session::exists($key)){
                return Session::get($key);
            }else{
                return self::createToken($key);
            }
        }

        public static function checkToken($token, $checkingFrom = "token"){
            return Session::get($checkingFrom) == $token; 
        }

    }

?>