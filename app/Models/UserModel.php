<?php 

    namespace app\Models;
    use app\Core\Model;

    class UserModel extends Model{
        
        /**
         *  @param String $username Username to get info from
         *  @param String $fields Database "users" tabel's fields to get seperated with comma(,) Ex: u_name,u_age, u..
         */
        public function getUserInfoByEmail($email, $fileds = "*"){
            $result = $this->fetch("SELECT $fileds FROM users WHERE email=:email", array(
                ":email" => $email
            ));
            return $result;
        }

        public function emailExists($value){
            $result = $this->fetchColumn("SELECT COUNT(*) FROM users WHERE email=:email", array(":email"=>$value));
            if($result == 0){
                return false;
            }else{
                return true;
            }
        }

        public function getUserInfoByUserId($userId, $fileds = "*"){
            $result = $this->fetch("SELECT $fileds FROM users WHERE user_id=:user_id", array(
                ":user_id" => $userId
            ));
            return $result;
        }

        public function usernameExists($value){
            $result = $this->fetchColumn("SELECT COUNT(*) FROM users WHERE username=:username", array(":username"=>$value));
            if($result == 0){
                return false;
            }else{
                return true;
            }
        }

        public function getCurrUserLevel(){
            $userInfo = \app\Utils\Session::get("user_info");
            if(empty($userInfo)) return false;
            $result = $this->fetchColumn("SELECT user_level FROM users WHERE user_id=:user_id", array(
                ":user_id" => $userInfo["user_id"]
            ));
            return $result; 
        }
    }

?>