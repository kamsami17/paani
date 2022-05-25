<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Classe::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		$classe = new Classe();
        $classe->name = htmlspecialchars($_POST['name']);
        $classe->ecole = $_SESSION['user']->ecole;
        $classe->create($bdd);
		echo 'create';
      break;
    case 'update':
		$classe = new Classe();
        $classe->id = htmlspecialchars($_POST['id']);
        $classe->name = htmlspecialchars($_POST['name']);
        $classe->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Classe::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>