<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Message::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		$message = new Message();
        $message->classe = htmlspecialchars($_POST['classe']);
        $message->message = htmlspecialchars($_POST['message']);
        $message->surveillant = htmlspecialchars($_POST['surveillant']);
        $message->create($bdd);
		echo 'create';
      break;
    case 'update':
		$message = new Message();
        $message->id = htmlspecialchars($_POST['id']);
        $message->classe = htmlspecialchars($_POST['classe']);
        $message->message = htmlspecialchars($_POST['message']);
        $message->surveillant = htmlspecialchars($_POST['surveillant']);
        $message->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Message::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>