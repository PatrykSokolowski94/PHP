<?php
session_start();
include ('Chat.php');

$chat = new Chat();
if($_POST['action'] == 'insert_chat') {
	$chat->insertChat($_POST['to_user_id'], $_SESSION['clientid'], $_POST['chat_message']);
}
if($_POST['action'] == 'show_chat') {
	$chat->showUserChat($_SESSION['clientid'], $_POST['to_user_id']);
}
if($_POST['action'] == 'update_user_chat') {

	$conversation = $chat->getUserChat($_SESSION['clientid'], $_POST['to_user_id']);
	$data = array(
		"conversation" => $conversation			
	);
	echo json_encode($data);
}
?>
