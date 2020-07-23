<?php 

    namespace app\Utils;
    use app\Core\Route;

    class Routes{

        public static function load(){
            foreach(Config::get("ROUTES") as $item){
                if(!isset($item[0]) || !isset($item[1])){
                    return;
                }

                Route::run($item[0], $item[1], isset($item[2]) ? $item[2] : "get");  
            }

            Route::end();
        }
    }

?>