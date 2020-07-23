<?php 

    namespace app\Models;
    use app\Core\Model;

    class LoginModel extends Model{
        
        public function doesUserExists($email, $password) : bool{
            $result = $this->fetchColumn("SELECT password, user_id FROM users WHERE email=:email", array(
                ":email" => $email
            ));
            if(!empty($result)){
                if(password_verify($password, $result)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        /**
         *  Log-in user
         * 
         *  @param Array $userInfo Array contains some user info
         * 
         *  $userInfo = [
         *      "user_info" => (info),
         *      "username" => (info),
         *  ]
         */
        public function login(Array $userInfo) : void{
            \app\Utils\Session::set("user_info",[
                "user_id"=>$userInfo["user_id"],
                "username"=>$userInfo["username"]
            ]);
        }
    }

?>