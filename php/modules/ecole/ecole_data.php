<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Ecole::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		$ecole = new Ecole();
        $ecole->name = htmlspecialchars($_POST['name']);
        $ecole->code = htmlspecialchars($_POST['code']);
        $ecole->create($bdd);
		echo 'create';
      break;
    case 'update':
		$ecole = new Ecole();
        $ecole->id = htmlspecialchars($_POST['id']);
        $ecole->name = htmlspecialchars($_POST['name']);
        $ecole->code = htmlspecialchars($_POST['code']);
        $ecole->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Ecole::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>