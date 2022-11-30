<?php
class Chat{
    private $host  = 'xxxxxxxxxx.mysql.db';
    private $user  = 'xxxxxxxxxxxxx22';
    private $password   = "xxxxxxxx";
    private $database  = "xxxxxxxxxx";      
    private $chatTable = 'chat';
	private $chatUsersTable = 'clients';
	private $chatLoginDetailsTable = 'clients';
	private $dbConnect = false;
    public function __construct(){
        if(!$this->dbConnect){ 
            $conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            }else{
                $this->dbConnect = $conn;
            }
        }
    }
	private function getData($sqlQuery) {
		
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. $sqlQuery);
		}
		$data= array();
		while ($row = mysqli_fetch_array($result)) {
			$data[]=$row;            
		}
		return $data;
	}
	private function getNumRows($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error());
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}
	public function loginUsers($username, $password){
		$sqlQuery = "
			SELECT id, username 
			FROM ".$this->chatUsersTable." 
			WHERE username='".$username."' AND password='".$password."'";		
        return  $this->getData($sqlQuery);
	}		
	public function chatUsers($userid){
		$sqlQuery = "
			SELECT * FROM `boosterzy`
			WHERE ID = '$userid'";
		return  $this->getData($sqlQuery);
	}
	public function boosterClients($userid){
		$sqlQuery = "
			SELECT * FROM `clients`
			WHERE IDBOOSTERA = '$userid'";
		return  $this->getData($sqlQuery);
	}
	public function getUserDetails($userid){
		$sqlQuery = "
			SELECT * FROM ".$this->chatUsersTable." 
			WHERE id = '$userid'";
		return  $this->getData($sqlQuery);
	}


	public function insertChat($reciever_userid, $user_id, $chat_message) {		
		$date = date("Y-m-d H:i:s");
		$sqlInsert = "
			INSERT INTO ".$this->chatTable." 
			(reciever_userid, sender_userid, message, date) 
			VALUES ('".$reciever_userid."', '".$user_id."', '".$chat_message."', '".$date."')";
		$result = mysqli_query($this->dbConnect, $sqlInsert);
		if(!$result){
			return ('Error in query: '. mysqli_error());
		} else {
			$conversation = $this->getUserChat($user_id, $reciever_userid);
			$data = array(
				"conversation" => $conversation			
			);
			echo json_encode($data);	
		}
	}
	public function getUserChat($from_user_id, $to_user_id) {
		$toUserAvatar = 'user3.jpg';		
		$fromUserAvatar = $toUserAvatar;
		$sqlQuery = "
			SELECT * FROM ".$this->chatTable." 
			WHERE (sender_userid = '".$from_user_id."' 
			AND reciever_userid = '".$to_user_id."') 
			OR (sender_userid = '".$to_user_id."' 
			AND reciever_userid = '".$from_user_id."') 
			ORDER BY timestamp ASC";

		$userChat = $this->getData($sqlQuery);	

		$conversation = '<ul>';
		foreach($userChat as $chat){
			$user_name = '';
			if($chat["sender_userid"] == $from_user_id) {
				$conversation .= '<li class="sent">';
				$conversation .= '<img width="22px" height="22px" src="img/user3.jpg" alt="" />';
			} else {
				$conversation .= '<li class="replies">';
				$conversation .= '<img width="22px" height="22px" src="img/user3.jpg" alt="" />';
			}			
			$conversation .= '<p style="background:#f5f5f5;color:black;font-size:7px;width:80px;">'.$chat["date"].'</p><br><p>'.$chat["message"].'</p>';			
			$conversation .= '</li>';
		}		
		$conversation .= '</ul>';

		return $conversation;
	}
	public function showUserChat($from_user_id, $to_user_id) {		
		$userDetails = $this->getUserDetails($to_user_id);
		$toUserAvatar = '';
		foreach ($userDetails as $user) {
			$toUserAvatar = $user['avatar'];
			$userSection = '<img src="img/user5.jpg" alt="" />
				<p>Rozmowa</p>';
		}		

		$conversation = $this->getUserChat($from_user_id, $to_user_id);	

		$sqlUserUpdate = "
			UPDATE ".$this->chatUsersTable." 
			SET current_session = '".$to_user_id."' 
			WHERE userid = '".$from_user_id."'";
		mysqli_query($this->dbConnect, $sqlUserUpdate);		
		$data = array(
			"userSection" => $userSection,
			"conversation" => $conversation			
		 );
		 echo json_encode($data);		
	}	
	
	
	public function fetchIsTypeStatus($userId){
		$sqlQuery = "
		SELECT is_typing FROM ".$this->chatLoginDetailsTable." 
		WHERE userid = '".$userId."' ORDER BY last_activity DESC LIMIT 1"; 
		$result =  $this->getData($sqlQuery);
		$output = '';
		foreach($result as $row) {
			if($row["is_typing"] == 'yes'){
				$output = ' - <small><em>Typing...</em></small>';
			}
		}
		return $output;
	}		
	public function insertUserLoginDetails($userId) {		
		$sqlInsert = "
			INSERT INTO ".$this->chatLoginDetailsTable."(userid) 
			VALUES ('".$userId."')";
		mysqli_query($this->dbConnect, $sqlInsert);
		$lastInsertId = mysqli_insert_id($this->dbConnect);
        return $lastInsertId;		
	}	
	
}
?>
