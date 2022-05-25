<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Fees::read($bdd, htmlspecialchars($_POST['id'])));
		break;
  case 'create':
		$fees = new Fees();
        $fees->classe = htmlspecialchars($_POST['classe']);
        $fees->montant = htmlspecialchars($_POST['montant']);
        $fees->create($bdd);
		echo 'create';
      break;
  case 'update':
		$fees = new Fees();
        $fees->id = htmlspecialchars($_POST['id']);
        $fees->classe = htmlspecialchars($_POST['classe']);
        $fees->montant = htmlspecialchars($_POST['montant']);
        $fees->update($bdd);
        echo 'update';
      break;
  case 'delete':
		Fees::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;
  case 'gen_fich':
    $out = shell_exec('python -W ignore ../../../py/gen_fiche_scolarite.py '.htmlspecialchars($_POST['classe']).' 1');
    echo $out;
    break;

  }
?>