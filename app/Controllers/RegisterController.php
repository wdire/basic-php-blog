<?php 
    namespace app\Controllers;
    use app\Core\Controller;
    use app\Utils as U;

use function PHPSTORM_META\type;

class RegisterController extends Controller{

        private $registerInfo = [];
        private $errors = [];

        public function index(){
            if(U\Auth::checkPermission()) header("Location:./");
            $this->View->assign("token",U\Auth::getToken("token"));
            $this->View->addExtra("<link rel='stylesheet' href='https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css'>");
            $this->View->addExtra("<link rel='stylesheet' href='assets/css/login.css'>");
            $this->View->renderWithoutFooter("register");
        }

        public function _register(){
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
                        if(empty($this->registerInfo)){
                            U\JsonOut::echo(["error_only"=>"Bir hata oluştu"]);
                            return;
                        }
                        $this->registerInfo["password"] = password_hash($this->registerInfo["password"], PASSWORD_DEFAULT);
                        $this->registerInfo["user_id"] = uniqid();

                        $isSuccess = $this->model("Register")->createUser($this->registerInfo);
                        if($isSuccess){
                            $this->model("Login")->login($this->registerInfo);
                            U\JsonOut::echo(["status"=>"success"]);
                        }else{
                            U\JsonOut::echo(["status"=>"fail","error_only"=>"Bir hata oluştu"]);
                        }
                    }

                }else{
                    U\JsonOut::echo(["status"=>"fail","error_only"=>"Geçersiz token"]);
                }
            }else{
                U\JsonOut::echo(["status"=>"fail","error_only"=>"Geçersiz istek"]);
            }
        }

        private function checkUserInfo(){

            $registerControl = U\Config::get("REGISTER_CONTROL");

            if(!U\Validator::arrKeysEqual($_POST,$registerControl)){
                $this->errors[] = "Fields are incorrect";
                return false;
            }

            $model = $this->model("User");

            foreach($_POST as $key => $value){
                $curr = $registerControl[$key];
                if($curr === false) continue;
                if(empty($value)){
                    $this->errors[] = "Fill the Fields.";
                    return false;
                }
                if(U\Validator::size($value,$curr["min"], $curr["max"])){
                    if($key == "email"){
                        if(!U\Validator::email($value)){
                            $this->errors[] = $curr["text"] . " hatalı";
                            continue;
                        }else if($model->emailExists($value)){
                            $this->errors[] = $curr["text"] . " is already using by someone else.";
                            continue;
                        }
                    }else if($key == "username"){
                        if(!U\Validator::username($value)){
                            $this->errors[] = $curr["text"] . " is incorrect. Use only letters(a-z), numbers(0-9) and special characters(-_.@).";
                            continue;
                        }else if($model->usernameExists($value)){
                            $this->errors[] = $curr["text"] . " is already using by someone else.";
                            continue;
                        }
                    }
                    else if($key == "password" && !U\Validator::password($value)){
                        $this->errors[] = $curr["text"] . " must include at least one uppercase letter and one lowercase letter.";
                        continue;
                    }

                    $this->registerInfo[$key] = trim(htmlspecialchars($value));
                }else{
                    $this->errors[] = $curr["text"] . " must be between " . $curr["min"] . " and " . $curr["max"];
                }
            }
        }

    }

?>