<?php 

    namespace app\Controllers;
    use app\Core\Controller;
    use app\Utils as U;

    class LoginController extends Controller{

        private $loginInfo = [];
        private $errors = [];

        public function index(){
            if(U\Auth::checkPermission()) header("Location:./");
            $this->View->assign("token",U\Auth::getToken("token"));
            $this->View->addExtra("<link rel='stylesheet' href='https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css'>");
            $this->View->addExtra("<link rel='stylesheet' href='assets/css/login.css'>");
            $this->View->renderWithoutFooter("login");
        }


        public function _login(){
            if(U\Auth::checkPermission()) header("Location:./");
            U\Spam::check();
            if(U\Spam::isBanned()){
                //TODO: Change this to write ban remaining time in form
                U\JsonOut::echo(["error_only"=>"You made too many attempts, please try again later.."]);
                return;
            }
            
            if(isset($_POST) && !empty($_POST)){
                if(isset($_POST["token"]) && U\Auth::checkToken($_POST["token"])){
                    $this->checkUserInfo();

                    if(!empty($this->errors)){
                        U\JsonOut::echo(["status"=>"fail","errors"=>$this->errors]);
                    }else{
                        if(empty($this->loginInfo)){
                            U\JsonOut::echo(["error_only"=>"Bir hata oluştu"]);
                            return;
                        }

                        $isSuccess = $this->model("Login")->doesUserExists($this->loginInfo["email"],$this->loginInfo["password"]);
                        if($isSuccess){
                            $userInfo = $this->model("User")->getUserInfoByEmail($this->loginInfo["email"],"user_id,username");
                            $this->model("Login")->login($userInfo);
                            U\JsonOut::echo(["status"=>"success"]);
                        }else{
                            U\JsonOut::echo(["status"=>"fail","error_only"=>"Username or Password is incorrect."]);
                        }
                    }
                }else{
                    U\JsonOut::echo(["status"=>"fail","error_only"=>"Geçersiz istek"]);
                }
            }else{
                U\JsonOut::echo(["status"=>"fail","error_only"=>"Geçersiz istek"]);
            }
        }

        private function checkUserInfo(){
            $loginControl = U\Config::get("LOGIN_CONTROL");

            if(!U\Validator::arrKeysEqual($_POST,$loginControl)){
                $this->errors[] = "Alanlar hatalı";
                return false;
            }

            foreach($_POST as $key => $value){
                $curr = $loginControl[$key];
                if($curr === false) continue;
                if(empty($value)){
                    $this->errors[] = "Alanları boş bırakmayın";
                    return false;
                }

                if(U\Validator::size($value,$curr["min"], $curr["max"])){
                    if($key == "email" && !U\Validator::email($value)){
                        $this->errors[] = $curr["text"] . " hatalı";
                        continue;
                    }else if($key == "password" && !U\Validator::password($value)){
                        $this->errors[] = $curr["text"] . " must include at least one uppercase letter and one lowercase letter.";
                        continue;
                    }
                    $this->loginInfo[$key] = trim(htmlspecialchars($value));
                }else{
                    $this->errors[] = $curr["text"] . " must be between " . $curr["min"] . " and " . $curr["max"];
                }
            }

            /*$username = htmlspecialchars($_POST["username"]);
            $password = htmlspecialchars($_POST["password"]);

            $model = $this->model("LoginModel");
            
            $errors = [];

            if(empty($username) || empty($password)){
                echo json_encode(array("errors"=>["Alanları boş bırakmayın."]), JSON_UNESCAPED_UNICODE);
            }

            if(!$this->checkLength($username, 2, 50)){
                array_push($errors, "Kullanıcı Adı hatalı");
            }

            if(!$this->checkLength($password, 6, 32)){
                array_push($errors, "Şifre hatalı");
            }

            if(empty($errors)){
                $isExists = $model->doesUserExists($username, $password);
                
                if($isExists){
                    $userModel = $this->model("UserModel");
                    $_SESSION["user_info"] = $userModel->getUserInfoByUsername($username);
                    echo "success";
                }else{
                    echo json_encode(array("errors"=>["Kullanıcı adı veya Şifre yanlış"]), JSON_UNESCAPED_UNICODE);
                }
            }else{
                echo json_encode(array("errors"=>$errors), JSON_UNESCAPED_UNICODE);
            }*/
        }

        private function checkLength($value, $min, $max){
            if(strlen($value) < $min || strlen($value) > $max){
                return false;
            }
            return true;
        }

        public function signout(){
            U\Session::delete("user_info");
            header("Location:./");
        }

    }


?>