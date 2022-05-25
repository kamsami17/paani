<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Trimestre::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		$trimestre = new Trimestre();
        $trimestre->classe = $_SESSION['classe']->id;
        $trimestre->lib = htmlspecialchars($_POST['lib']);
        $trimestre->create($bdd);
		echo 'create';
      break;
    case 'update':
		$trimestre = new Trimestre();
        $trimestre->id = htmlspecialchars($_POST['id']);
        $trimestre->classe = $_SESSION['classe']->id;
        $trimestre->lib = htmlspecialchars($_POST['lib']);
        $trimestre->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Trimestre::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>