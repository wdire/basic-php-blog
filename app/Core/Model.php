<?php 

    namespace app\Core;
    use app\Utils as U;

    class Model extends U\Database{

        public function __construct(){
            parent::__construct();
        }

        private function init($sql, $parameters = []){
            try{
                $query = $this->conn->prepare($sql);
                $query->execute($parameters);
                return $query;
            }catch(\PDOException $e){
                U\Log::do("Database query error: ".$e->getMessage());
            }
        }

        public function query($sql, $parameters = []){
            $query = $this->init($sql, $parameters);
            
            $statement = explode(" ", $sql);
            $statement = strtolower($statement[0]);

            if ($statement === 'select' || $statement === 'show'){
                return $query->fetchAll();
            }
            else if ($statement === 'insert' || $statement === 'update' || $statement === 'delete'){
                return $query->rowCount();
            }	
            else {
                return null;
            }
        }

        public function fetchColumn($sql, $parameters = []){
            $query = $this->init($sql, $parameters);
            return $query->fetchColumn();
        }

        public function fetch($sql, $parameters = ""){
            $query = $this->init($sql, $parameters);
            return $query->fetch();
        }
    }

?>