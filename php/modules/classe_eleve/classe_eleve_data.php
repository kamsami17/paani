<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Classe_eleve::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		    $classe_eleve = new Classe_eleve();
        $classe_eleve->classe = htmlspecialchars($_POST['classe']);
        $classe_eleve->eleve = htmlspecialchars($_POST['eleve']);
        $classe_eleve->annee = htmlspecialchars($_POST['annee']);
        $classe_eleve->create($bdd);
		echo 'create';
      break;
    case 'update':
		$classe_eleve = new Classe_eleve();
        $classe_eleve->id = htmlspecialchars($_POST['id']);
        $classe_eleve->classe = htmlspecialchars($_POST['classe']);
        $classe_eleve->eleve = htmlspecialchars($_POST['eleve']);
        $classe_eleve->annee = htmlspecialchars($_POST['annee']);
        $classe_eleve->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Classe_eleve::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>