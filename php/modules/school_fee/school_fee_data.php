<?php
  include_once('../../connectdb.php');
  include_once('../../SimpleXLSX.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	case 'import':
      $name = upload($_FILES['liste'], 'scholarite');
      if ( $xlsx = SimpleXLSX::parse('../../'.$name) ) {
        foreach($xlsx->rows() as $row){
          if(strtolower($row[0])!='matricule' && strtolower(substr($row[0], 0, 5))!='liste'){
            $eleve = Eleve::getid($bdd, $row[0]);
            $fee = Fees::readclasse($bdd, $_SESSION['sel_classe']);
            $school_fee = new School_fee();
            $school_fee->classe = $_SESSION['sel_classe'];
            $school_fee->eleve = $eleve;
            $school_fee->montant = $row[3];
            $school_fee->reste = $fee->montant - $row[3];
            $school_fee->create($bdd);
            // $pv = $row[4]==''? Eleve::genCode($bdd) : $row[4];
            // $eleve = new Eleve();
            // $eleve->nom = $row[0];
            // $eleve->prenom = $row[1];
            // $eleve->sexe = $row[2];
            // $eleve->passwd = $pv;
            // $eleve->uname = $pv;
            // $eleve->date_naissance = pdate($row[3]);
            // $eleve->matricule = $pv;
            // $eleve->parents = 0;
            // $eleve->create($bdd);
            // $classe_eleve = new Classe_eleve();
            // $classe_eleve->classe = $_SESSION['classe']->id;
            // $classe_eleve->eleve = $eleve->id;
            // $classe_eleve->annee = $_SESSION['annee']; // change later
            // $classe_eleve->create($bdd);
          }
        }
        echo 'done';
      }else{
        echo 'error';
      }
    break;
	case 'read':
		echo json_encode(School_fee::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		    $school_fee = new School_fee();
        $school_fee->classe = $_SESSION['sel_classe'];//htmlspecialchars($_POST['classe']);
        $school_fee->eleve = htmlspecialchars($_POST['eleve']);
        $school_fee->montant = htmlspecialchars($_POST['montant']);
        $school_fee->reste = htmlspecialchars($_POST['reste']);
        $school_fee->create($bdd);
		echo 'create';
      break;
    case 'update':
		$school_fee = new School_fee();
        $school_fee->id = htmlspecialchars($_POST['id']);
        $school_fee->classe = htmlspecialchars($_POST['classe']);
        $school_fee->eleve = htmlspecialchars($_POST['eleve']);
        $school_fee->montant = htmlspecialchars($_POST['montant']);
        $school_fee->reste = htmlspecialchars($_POST['reste']);
        $school_fee->update($bdd);
        echo 'update';
      break;
    case 'delete':
		School_fee::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>