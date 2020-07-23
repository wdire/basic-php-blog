<?php 

    namespace app\Core;

    class Route{

        private static $routeWorked = false;

        public static function parse_url(){
            $dirname = dirname($_SERVER["SCRIPT_NAME"]);
            $basename = basename($_SERVER["SCRIPT_NAME"]);
            if($basename != "/" &&  $dirname != "/"){
                $request_uri = str_replace([$dirname, $basename], null, $_SERVER["REQUEST_URI"]);
                return $request_uri;
            }
            return $_SERVER["REQUEST_URI"];
        }
        
        // Route -> {ControllerAdı}@{ÇağırılanFonksiyon}
        public static function run($url, $callback, $method = "get"){
            if(self::$routeWorked == true){
                return;
            }
            $patterns = [
                "{url}" => "([a-zA-Z0-9-]+)",
                "{id}" => "([0-9]+)",
            ];

            $method = explode("|", strtoupper($method));
            

            if(in_array($_SERVER["REQUEST_METHOD"], $method)){
                
                // Pattern kullanılırsa değeriyle değiştir
                $url = str_replace(array_keys($patterns), array_values($patterns), $url);

                $request_uri = self::parse_url();

                if(empty($request_uri)) $request_uri = "/";

                if($request_uri[0] != "/"){
                    $request_uri = "/".$request_uri;
                }

                /*echo $url;
                echo " - ";
                echo $request_uri;
                echo "<br>";*/

                // Şuanda sitenin olduğu URL istenilen url ise:
                if(preg_match("@^\/?".$url."\/?$@", $request_uri, $parameters)){
                    self::$routeWorked = true;
                    //print_r($parameters);
                    unset($parameters[0]);
                    // Controller adı yerine fonksiyon girdiyse onu çalıştır
                    if(is_callable($callback)){
                        call_user_func_array($callback, $parameters);
                        return;
                    }
                    
                    if(!strpos($callback ,"@")){
                        return;
                    }

                    $controller = explode("@", $callback);
                    $controllerFile = 'app\\Controllers\\'.$controller[0];
                    
                    $controllerClass = new $controllerFile;
                    if(!method_exists($controllerClass, $controller[1])){
                        return;
                    }
                    
                    call_user_func_array([$controllerClass, $controller[1]], $parameters);   
                }
            }
        }

        public static function end(){
            if(self::$routeWorked == false){
                /*echo "<pre>";
                print_r($_SERVER);
                echo "</pre>";*/
                header("Location:../");
            }
        }

    }

?>