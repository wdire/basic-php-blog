<?php 

    namespace app\Core;
    use app\Utils as U;

    class View{

        private $views_path = __PATH__ . "/app/Views/";
        private $_extra = [];
        private $_title;
        private $url;
        private $userInfo;
        private $_metas = [];

        public function __construct(){
            $this->url = U\Url::base();
            $this->userInfo = U\Session::get("user_info");
            if($this->userInfo !== false){
                $model = new \app\Models\UserModel();
                $this->userInfo["user_level"] = $model->getCurrUserLevel();
            }
        }

        private function init($name){
            $file_path = $this->views_path . $name."View.php";
            if(file_exists($file_path)){
                return $file_path;
            }
            return false;
        }

        public function assign($key, $value){
            $this->{$key} = $value;
        }

        public function addMetas($metaName, $content){
            $this->_metas[$metaName] = $content;
        }

        public function addExtra($extra){
            $this->_extra[] = $extra;
        }

        public function render($name, $title = null){
            if($file_path = $this->init($name)){
                $this->_title = $title;
                require_once $this->views_path . "static/_header.php";
                require_once $file_path;
                require_once $this->views_path . "static/_footer.php";
                require_once $this->views_path . "static/_closetags.php";
            }
        }

        public function renderWithoutFooter($name, $title = null){
            if($file_path = $this->init($name)){
                $this->_title = $title;
                require_once $this->views_path . "static/_header.php";
                require_once $file_path;
                require_once $this->views_path . "static/_closetags.php";
            }
        }
    }

?>