<?php 
    
    define("__PATH__",__DIR__);
    
    define("__URL__", ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == "https") ? "https" : "http"). "://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER["SCRIPT_NAME"]),"/\\"));
    require __PATH__."/app/init.php";
    
?>
