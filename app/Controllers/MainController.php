<?php 

    namespace app\Controllers;
    use app\Core\Controller;
    use app\Utils as U;

    class MainController extends Controller{

        public function index(){
            $content = $this->getPage();
            $this->View->assign("content", $content);
            $this->View->addExtra("<link rel='stylesheet' href='https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css'>");
            $this->View->render("main","Home");
        }

        public function getPage(){
            preg_match('@page/([0-9]+)@',$_SERVER["REQUEST_URI"], $requri);
            $currPage = empty($requri) ? 1 : $requri[1];
            $model = $this->model("Article");

            $articlesPerPage = 5;
            $totalArticles = $model->totalArticles();
            $currPageStartResult = ($currPage-1)*$articlesPerPage;
            
            $totalPages = ceil($totalArticles / $articlesPerPage);
            $pageContent = $model->getPageArticles($currPageStartResult, $articlesPerPage);

            return [
                "currentPage"=>$currPage,
                "totalPages"=>$totalPages,
                "pageContent"=>$pageContent
            ];
        }

        public function aboutme(){
            $this->View->render("aboutme","About Me");
        }

        public function contact(){
            $this->View->assign("token",U\Auth::getToken("token"));
            $this->View->render("contact","Contact");
        }

        public function sendContact(){
            if(isset($_POST) && !empty($_POST) && isset($_POST["token"])){
                if(U\Auth::checkToken($_POST["token"])){
                    if(!isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["subject"]) || !isset($_POST["message"])) header("Location:".__URL__."/contact?failed");
                    $data = [
                        "name"=>$_POST["name"],
                        "email"=>$_POST["email"],
                        "subject"=>$_POST["subject"],
                        "message"=>$_POST["message"]
                    ];

                    // Old ways
                    if(
                        strlen($data["name"]) < 3 || strlen($data["name"]) > 30 || 
                        strlen($data["email"]) < 4 || strlen($data["email"]) > 60 || 
                        strlen($data["subject"]) < 3 || strlen($data["subject"]) > 30 || 
                        strlen($data["message"]) < 10 || strlen($data["message"]) > 1000
                    ){
                        header("Location:".__URL__."/contact");
                    }
                    $result = $this->model("Main")->addUserContactMessage($data);
                    if($result){
                        header("Location:".__URL__."/contact");
                    }else{
                        header("Location:".__URL__."/contact");  
                    }
                }
            }
            header("Location:".__URL__."/contact?failed");
        }

    }

?>