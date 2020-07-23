<?php 

    namespace app\Utils;

    class Url{

        static public function base(){
            return rtrim(dirname($_SERVER["SCRIPT_NAME"]),"/\\");
        }

    }

?>