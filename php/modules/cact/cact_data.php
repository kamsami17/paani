<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Cact::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		$cact = new Cact();
        $cact->classe = htmlspecialchars($_POST['classe']);
        $cact->activity = htmlspecialchars($_POST['activity']);
        $cact->create($bdd);
		echo 'create';
      break;
    case 'update':
		$cact = new Cact();
        $cact->id = htmlspecialchars($_POST['id']);
        $cact->classe = htmlspecialchars($_POST['classe']);
        $cact->activity = htmlspecialchars($_POST['activity']);
        $cact->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Cact::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>