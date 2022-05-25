<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Discipline::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		$discipline = new Discipline();
        $discipline->eleve = htmlspecialchars($_POST['eleve']);
        $discipline->note = htmlspecialchars($_POST['note']);
        $discipline->surveillant = htmlspecialchars($_POST['surveillant']);
        $discipline->create($bdd);
		echo 'create';
      break;
    case 'update':
		$discipline = new Discipline();
        $discipline->id = htmlspecialchars($_POST['id']);
        $discipline->eleve = htmlspecialchars($_POST['eleve']);
        $discipline->note = htmlspecialchars($_POST['note']);
        $discipline->surveillant = htmlspecialchars($_POST['surveillant']);
        $discipline->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Discipline::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>