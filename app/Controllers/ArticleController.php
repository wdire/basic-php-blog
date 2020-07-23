<?php 

    namespace app\Controllers;
    use app\Core\Controller;
    use app\Utils as U;
    use app\Utils\Session;

class ArticleController extends Controller{

        public function index(){
            $model = $this->model("Article");
            $articleData = $this->getArticle($model);
            $commentsData = $this->getComments($model,$articleData["article_id"]);
            $totalComments = $model->getArticleCommentsCount($articleData["article_id"]);
            $this->View->addExtra("<link rel='stylesheet' href='https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css'>");
            $this->View->addMetas("og:description",trim(mb_substr(preg_replace("/(&nbsp;)+/"," ",strip_tags(htmlspecialchars_decode($articleData["article_content"]))),0,140,"UTF-8")));
            $this->View->assign("commentToken",U\Auth::getToken("token"));
            $this->View->assign("articleData", $articleData);
            $this->View->assign("commentsData", $commentsData);
            $this->View->assign("totalComments", $totalComments);
            $this->View->render("article", $articleData["article_title"]);
        }

        private function getComments($model, $articleId){
            $comments = $model->getArticleComments($articleId);
            $newComments = [];
            $userModel = $this->model("User");
            foreach($comments as $key => $value){
                $newComments[] = $this->makeComment($value,$userModel);;
            }
            return $newComments;
        }

        private function makeComment($comment, $userModel){
            $curComment = $comment;
            $userInfo = $userModel->getUserInfoByUserId($comment["user_id"], "username");
            $curComment["username"] = $userInfo["username"];
            $curComment["shortDate"] = strftime('%B %e, %Y - %H:%M', strtotime($comment["date"]));
            return $curComment;
        }

        private function getArticle($model){
            preg_match('@article/([a-zA-Z0-9-]+)@',$_SERVER["REQUEST_URI"], $requri);
            if(empty($requri)){
                header("Location:/");
            }

            $articleUrl = $requri[1];
            
            $model = $this->model("Article");
            return $model->getArticle($articleUrl);
        }
        
        public function _action(){
            if(!U\Auth::checkPermission()) return;
            if(U\Spam::isBanned()){
                U\JsonOut::echo(["status"=>"failed","banned"]);
                return;
            }
            if(!isset($_POST) || empty($_POST)){
                U\JsonOut::echo(["status"=>"failed"]);
            }else{
                if(isset($_POST["token"]) && U\Auth::checkToken($_POST["token"],"token")){
                    if(empty($_POST["articleUrl"])){
                        U\JsonOut::echo(["status"=>"failed","article url"]);
                        return;
                    }
                    $action = $_POST["action"];
                    if($action == "addComment"){
                        $this->_addComment();
                    }else if($action == "deleteComment"){
                        $this->_deleteComment();
                    }else{
                        U\JsonOut::echo(["status"=>"failed","no action"]);
                    }
                }else{
                    U\JsonOut::echo(["status"=>"failed","no token"]);
                }
            }
        }

        private function _addComment(){
            U\Spam::check();
            if(empty($_POST["commentContent"])){
                U\JsonOut::echo(["status"=>"failed","content empty"]);
                return;
            }

            $commentBody = htmlspecialchars(trim($_POST["commentContent"]));
            $model = $this->model("Article");
            $articleId = $model->getArticleIdByUrl($_POST["articleUrl"]);
            $data = [
                "articleId"=>$articleId,
                "user_id"=>U\Session::get("user_info")["user_id"],
                "comment"=>$commentBody
            ];
            if($model->addComment($data)){
                U\JsonOut::echo(["status"=>"success"]);
            }else{
                U\JsonOut::echo(["status"=>"failed","database"]);
                return;
            }
        }

        private function _deleteComment(){
            if(empty($_POST["commentId"])){
                U\JsonOut::echo(["status"=>"failed","id empty"]);
                return;
            }

            $uInfo = U\Session::get("user_info");
            if(!$uInfo || !$uInfo["user_id"]){
                U\JsonOut::echo(["status"=>"failed","user empty"]);
                return;
            }

            $userId =$uInfo["user_id"];
            $commentId = $_POST["commentId"];
            $model = $this->model("Article");
            $articleId = $model->getArticleIdByUrl($_POST["articleUrl"]);

            $data = [
                "articleId"=>$articleId,
                "commentId"=>$commentId,
                "userId"=>$userId
            ];

            if($model->deleteComment($data)){
                U\JsonOut::echo(["status"=>"success"]);
            }else{
                U\JsonOut::echo(["status"=>"failed","database"]);
            }
        }
    }

?>