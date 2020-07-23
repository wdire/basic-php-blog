<?php   

    namespace app\Models;
    use app\Core\Model;

    class MainModel extends Model{
        /**
         *  Add User Contact Message to Database
         *  @param Array $data Array that contains message info
         *  @param String $data[name]
         *  @param String $data[email]
         *  @param String $data[subject]
         *  @param String $data[message]
         *  @return Bool Returns true on success false on fail
         */
        public function addUserContactMessage(Array $data) : bool{
            $result = $this->query("INSERT INTO contactmessages (name, email, subject, message) VALUES(:name, :email, :subject, :message)",[
                ":name"=>$data["name"],
                ":email"=>$data["email"],
                ":subject"=>$data["subject"],
                ":message"=>$data["message"]
            ]);
            if($result){
                return true;
            }else{
                return false;
            }
        }
    }

?>