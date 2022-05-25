<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Annee::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		$annee = new Annee();
        $annee->name = htmlspecialchars($_POST['name']);
        $annee->create($bdd);
		echo 'create';
      break;
    case 'update':
		$annee = new Annee();
        $annee->id = htmlspecialchars($_POST['id']);
        $annee->name = htmlspecialchars($_POST['name']);
        $annee->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Annee::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>