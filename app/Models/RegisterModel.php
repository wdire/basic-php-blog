<?php 
    namespace app\Models;
    use app\Core\Model;

    class RegisterModel extends Model{

        public function createUser($userInfo){
            $result = $this->query("INSERT INTO users (user_id, username, name, surname, email, password) VALUES(:user_id, :username, :name, :surname, :email, :password)", $userInfo);
            if($result){
                return true;
            }else{
                return false;
            }
        }
    }

?>