<?php 

    namespace app\Utils;

    class Database{

        protected $conn;
        static $connected = false;

        public function __construct(){
            $dbData = Config::get("DB_OPTIONS");
            $this->connect($dbData);
        }

        public function connect($dbData){
            try{
                $this->conn = new \PDO("mysql:host=$dbData[host];dbname=$dbData[database];charset=$dbData[charset]", $dbData["username"], $dbData["password"], $dbData["options"]);
                $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$connected = true;
            }catch(\PDOException $e){
                self::$connected = false;
                echo "Database Error";
                Log::do("Database connection error: ".$e->getMessage());
                exit;
            }
        }

        public function disconnect(){
            $this->conn = null;
        }
    }

?>