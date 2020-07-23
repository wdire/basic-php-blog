<?php 

    namespace app\Utils;

    class Spam{

        public static function check(){
            if($lastCall = Session::get("lastCall")){
                if($banCall = Session::get("banCall")){
                    if(time() > $banCall["time"]){
                        Session::delete("banCall");
                    }
                    return;
                }
                $time = (int)$lastCall["time"];
                $diffLastTry = time() - $time;

                $lastCall["time"] = time();
                $lastCall["totalLastTryCount"] += 1;

                if($lastCall["fastLastTryCount"] > 5){
                    self::ban(time() * 60 * 5);
                    self::create();
                    return;
                }

                if($lastCall["totalLastTryCount"] > 10){
                    self::ban(time() * 60 * 10);
                    self::create();
                    return;
                }
                
                if($diffLastTry < 4){
                    $lastCall["fastLastTryCount"] += 1;
                }

                if($diffLastTry > 60){
                    $lastCall["fastLastTryCount"] = 0;
                }

                if($diffLastTry > 60 * 10){
                    $lastCall["fastLastTryCount"] = 0;
                }

                Session::set("lastCall", $lastCall);
            }else{
                self::create();
            }
        }

        private static function create(){
            Session::set("lastCall",[
                "time" => (string)time(),
                "totalLastTryCount" => 0,
                "fastLastTryCount" => 0
            ]);
        }

        private static function ban($time){
            Session::set("banCall", [
                "time" => $time
            ]);
        }

        /**
         * @return false|banRemoveSeconds If ban removed returns false or returns after how much seconds ban will be removed
         */
        public static function getBanRemoveTime(){
            if($banCall = Session::get("banCall")){
                $diff = $banCall["time"] - time();
                if($diff < 0){
                    Session::delete("banCall");
                    return false;
                }
                return $diff;
            }
        }

        public static function isBanned(){
            return Session::exists("banCall");
        }
    }

?>