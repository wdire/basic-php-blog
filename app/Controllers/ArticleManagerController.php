<?php 

    namespace app\Controllers;
    use app\Core\Controller;
    use app\Utils as U;
    // TODO: Change All Model accesses to use namespace name

    class ArticleManagerController extends Controller{

        public function index(){
            // TODO: Add Authorize User to every method
            U\Auth::redirectFailPermission("admin");
            $articles = $this->model("Article")->getArticlesForList();
            $this->View->assign("articles", $articles);
            $this->View->addExtra("<link rel='stylesheet' href='".__URL__."/assets/css/admin.css'>");
            $this->View->addExtra("<script src='".__URL__."/assets/js/admin.js'></script>");
            $this->View->renderWithoutFooter("admin/articleManager");
        }

        public function addView(){
            U\Auth::redirectFailPermission("admin");
            $this->View->addExtra("<link rel='stylesheet' href='".__URL__."/assets/css/admin.css'>");
            $this->View->addExtra("<script src='".__URL__."/app/Utils/tinymce/tinymce.min.js'></script>");
            $this->View->addExtra("<script src='".__URL__."/assets/js/admin.js'></script>");
            $this->View->renderWithoutFooter("admin/articleManagerAdd");
        }

        public function editView(){
            U\Auth::redirectFailPermission("admin");
            preg_match('@managearticles/edit/([a-zA-Z0-9-]+)@',$_SERVER["REQUEST_URI"], $requri);
            if(empty($requri)) header("Location:./");
            
            $articleUrl = $requri[1];
            $articleInfo = $this->model("Article")->getArticle($articleUrl);
            if(empty($articleInfo)) header("Location:/managearticles/");

            $this->View->assign("articleInfo",$articleInfo);
            $this->View->addExtra("<link rel='stylesheet' href='".__URL__."/assets/css/admin.css'>");
            $this->View->addExtra("<script src='".__URL__."/app/Utils/tinymce/tinymce.min.js'></script>");
            $this->View->addExtra("<script src='".__URL__."/assets/js/admin.js'></script>");
            $this->View->renderWithoutFooter("admin/articleManagerEdit");
        }

        public function _addArticle(){
            U\Auth::redirectFailPermission("admin");
            if(!$data = $this->processArticleData(["articleTitle", "articleImage", "articleContent"])){
                echo "error";
                return;
            }

            $model = $this->model("ArticleManager");

            if($model->createArticle($data)){
                echo "success";
            }else{
                echo "error";
            }
        }

        public function _editArticle(){
            U\Auth::redirectFailPermission("admin");
            if(!$data = $this->processArticleData(["articleTitle", "articleImage", "articleContent", "currArticleUrl"])){
                echo "error";
                return;
            }
            
            $model = $this->model("ArticleManager");

            $result = $model->editArticle($data);//$articleTitle, $articleContent, $articleImage, $articleUrl, $editToUrl

            if($result == 0){
                echo "equal";
            }else if($result){
                if($data["currArticleUrl"] != $data["articleUrl"]){
                    U\JsonOut::echo(["newUrl"=>$data["articleUrl"],"status"=>"success"]);
                }else{
                    echo "success";
                }
            }else{
                echo "error";    
            }
        }

        private function processArticleData(Array $fileds = []){
            if(!isset($_POST) || empty($_POST) || empty($fileds)) return false;

            $data = [];

            foreach($fileds as $key => $value){
                if(isset($_POST[$value])){
                    $data[$value] = htmlspecialchars($_POST[$value]);
                }else{
                    $data[$value] = null;
                }
            }

            if(empty($data["articleTitle"])){
                return false;
            }

            $articleUrl = mb_strtolower($data["articleTitle"], "UTF-8");
            $articleUrl = str_replace(
                ["ı","ğ","ü","ç","ö","ş"],
                ["i","g","u","c","o","s"],
                $articleUrl
            );

            $articleUrl = preg_replace("/[^a-z0-9]/", "-", $articleUrl);
            $articleUrl = preg_replace("/-+/","-", $articleUrl);
            $articleUrl = trim($articleUrl, "-");
            $data["articleUrl"] = $articleUrl;

            return $data;
        }

        public function deletePost(){
            U\Auth::redirectFailPermission("admin");
            preg_match('@managearticles/delete/([a-zA-Z0-9-]+)@',$_SERVER["REQUEST_URI"], $requri);
            if(empty($requri)){
                header("Location:./");
            }
            $articleUrl = $requri[1];
            if($articleId = $this->model("Article")->getArticleIdByUrl($articleUrl)){
                echo $this->model("ArticleManager")->deleteArticle($articleId);
            }
            header("Location:javascript://history.go(-1)");
        }

        public function uploadImage(){
            U\Auth::redirectFailPermission("admin");
            $allowedExtensions = ["png","jpg","gif","jpeg"];
            reset($_FILES);
            $temp = current($_FILES);
            if($temp["error"] > 0){
                U\JsonOut::echo(["error" => "Dosya boyutu çok fazla."]);
                return;
            }
            if(is_uploaded_file($temp["tmp_name"])){
                //if(isset($_SERVER["HTTP_ORIGIN"])){
                    //if(in_array($_SERVER["HTTP_ORIGIN"], ["http://localhost:90"])){
                    //    header("Access-Control-Allow-Origin:" . $_SERVER["HTTP_ORIGIN"]);
                    //}else{
                    //    header("HTTP/1.1 403 Origin Denied");
                    //    return;
                    //}
                //}

                $ext = pathinfo($temp["name"], PATHINFO_EXTENSION);
                
                $file_name = bin2hex(openssl_random_pseudo_bytes(8)) . ".$ext";
                if(!in_array($ext,$allowedExtensions)){
                    U\JsonOut::echo(["error" => "Dosya formatı kabul edilmiyor."]);
                    return;
                }
                $adress = "/assets/images/article/".$file_name;

                $urlAdress = $adress;
                $pathAdress = __PATH__ . $adress;
                move_uploaded_file($temp["tmp_name"],$pathAdress);
                U\JsonOut::echo(["location" => $urlAdress]);
            }else{
                header("Location:./");
            }
        }

    }

?>