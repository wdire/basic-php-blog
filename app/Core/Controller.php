<?php 

    namespace app\Core;

    class Controller{
        
        protected $View;

        public function __construct(){
            $this->View = new View();
        }

        public function model($name){
            $model_path = '\\app\\Models\\' . $name."Model";
            if(class_exists($model_path)){
                return new $model_path();
            }
            return false;
        }

    }

?>