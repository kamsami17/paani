<?php
  include_once('../../connectdb.php');
  include_once('../../SimpleXLSX.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
  case 'unset_parent':
    Eleve::set_parent($bdd, htmlspecialchars($_POST['eleve']), 0);
    echo 'done';
  break;
  case 'phone':
      $parent = new Parents();
      $parent->nom = '-';
      $parent->prenom = '-';
      $parent->contact = htmlspecialchars($_POST['telephone']);
      $parent->email = '-';
      $parent->passwd = sha1(md5(htmlspecialchars($_POST['telephone'])));
      $parent->ecole = $_SESSION['user']->ecole;
      $parent->create2($bdd);
      $r = $bdd->prepare('UPDATE eleve SET parents=? WHERE id=?');
      $r->execute(array($parent->id, htmlspecialchars($_POST['eleve'])));
      echo 'done';
    break;
  case 'import_alt':
    $name = upload($_FILES['classFile'], 'classe');
    if ( $xlsx = SimpleXLSX::parse('../../'.$name) ) {
      foreach($xlsx->rows() as $row){
        if(strtolower(substr($row[2], 0, 3))!='nom'){
          //$pv = $row[4]==''? Eleve::genCode($bdd) : $row[4];
          $nom = explode(' ', $row[0]); 
          
          $eleve = new Eleve();
          $eleve->nom = $nom[0];
          $pr = array_shift($nom);
          $eleve->prenom = implode(' ', $nom);
          $eleve->sexe = '';
          $eleve->passwd = $row[1];
          $eleve->uname = $row[1];
          $eleve->date_naissance = '';
          $eleve->matricule = $row[1];
          if($row[2]!=''){
            $contact = str_replace('-', '', trim($row[2]));
            $parent = new Parents();
            $parent->nom = '-';
            $parent->prenom = '-';
            $parent->contact = $contact;
            $parent->email = '-';
            $parent->passwd = sha1(md5($contact));
            $parent->ecole = $_SESSION['user']->ecole;
            $parent->create($bdd);
            $eleve->parents = $parent->id;
          }
          else{
            $eleve->parents = 0;
          }
          $eleve->create($bdd);

          $classe_eleve = new Classe_eleve();
          $classe_eleve->classe = $_SESSION['classe']->id;
          $classe_eleve->eleve = $eleve->id;
          $classe_eleve->annee = $_SESSION['annee']; // change later
          $classe_eleve->create($bdd);
        }
      }
      echo 'done';
    }else{
      echo 'error';
    }

    break;
    case 'import_alt2':
      $name = upload($_FILES['classFile'], 'classe');
      if ( $xlsx = SimpleXLSX::parse('../../'.$name) ) {
        foreach($xlsx->rows() as $row){
          // if(strtolower(substr($row[2], 0, 3))!='nom'){

            $eleve = new Eleve();
            if(strtolower($row[1]) == 'matricule'){ 
              $nom = explode(' ', $row[0]); 
              $eleve->nom = $nom[0];
              $pr = array_shift($nom);
              $eleve->prenom = implode(' ', $nom);
              $eleve->passwd = $row[1];
              $eleve->matricule = $row[1];
              $eleve->uname = $row[1];

              if($row[2]!=''){
                $contact = str_replace('-', '', trim($row[2]));
                $parent = new Parents();
                $parent->nom = '-';
                $parent->prenom = '-';
                $parent->contact = $contact;
                $parent->email = '-';
                $parent->passwd = sha1(md5($contact));
                $parent->ecole = $_SESSION['user']->ecole;
                $parent->create($bdd);
                $eleve->parents = $parent->id;
              }
              else{
                $eleve->parents = 0;
              }

            }
            else{ // $eleve = new Eleve();
              $eleve->nom = $row[0];
              $eleve->prenom = $row[1];
              $eleve->passwd = $row[2];
              $eleve->matricule = $row[2];
              $eleve->uname = $row[1];

              if($row[3]!=''){
                $contact = str_replace('-', '', trim($row[3]));
                $parent = new Parents();
                $parent->nom = '-';
                $parent->prenom = '-';
                $parent->contact = $contact;
                $parent->email = '-';
                $parent->passwd = sha1(md5($contact));
                $parent->ecole = $_SESSION['user']->ecole;
                $parent->create($bdd);
                $eleve->parents = $parent->id;
              }
              else{
                $eleve->parents = 0;
              }

            }
            $eleve->sexe = '';
            $eleve->date_naissance = '';
            
            $eleve->create($bdd);
  
            $classe_eleve = new Classe_eleve();
            $classe_eleve->classe = $_SESSION['classe']->id;
            $classe_eleve->eleve = $eleve->id;
            $classe_eleve->annee = $_SESSION['annee']; // change later
            $classe_eleve->create($bdd);
          // }
        }
        echo 'done';
      }else{
        echo 'error';
      }
  
      break;

  case 'import':
    $name = upload($_FILES['classFile'], 'classe');
    if ( $xlsx = SimpleXLSX::parse('../../'.$name) ) {
      foreach($xlsx->rows() as $row){
        if(strtolower($row[0])!='nom' && strtolower(substr($row[0], 0, 5))!='liste'){
          $pv = $row[4]==''? Eleve::genCode($bdd) : $row[4];
          $eleve = new Eleve();
          $eleve->nom = $row[0];
          $eleve->prenom = $row[1];
          $eleve->sexe = $row[2];
          $eleve->passwd = $pv;
          $eleve->uname = $pv;
          $eleve->date_naissance = pdate($row[3]);
          $eleve->matricule = $pv;
          $eleve->parents = 0;
          $eleve->create($bdd);

          $classe_eleve = new Classe_eleve();
          $classe_eleve->classe = $_SESSION['classe']->id;
          $classe_eleve->eleve = $eleve->id;
          $classe_eleve->annee = $_SESSION['annee']; // change later
          $classe_eleve->create($bdd);
        }
      }
      echo 'done';
    }else{
      echo 'error';
    }
    break;

  case 'set_parent':
      Eleve::set_parent($bdd, htmlspecialchars($_POST['eleve']), htmlspecialchars($_POST['parent']));
      echo 'done';
    break;
	case 'read':
		echo json_encode(Eleve::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
      $eleve = new Eleve();
      $eleve->nom = htmlspecialchars($_POST['nom']);
      $eleve->prenom = htmlspecialchars($_POST['prenom']);
      $eleve->date_naissance = pdate(htmlspecialchars($_POST['date_naissance']));
      $eleve->matricule = htmlspecialchars($_POST['matricule']);
      $eleve->uname = htmlspecialchars($_POST['matricule']);
      $eleve->sexe = htmlspecialchars($_POST['sexe']);
      $eleve->passwd = htmlspecialchars($_POST['matricule']);
      $eleve->parents = 0;
      $eleve->create($bdd);

      $classe_eleve = new Classe_eleve();
      $classe_eleve->classe = $_SESSION['classe']->id;
      $classe_eleve->eleve = $eleve->id;
      $classe_eleve->annee = $_SESSION['annee']; // change later
      $classe_eleve->create($bdd);
		echo 'create';
      break;
    case 'update':
		$eleve = new Eleve();
        $eleve->id = htmlspecialchars($_POST['id']);
        $eleve->nom = htmlspecialchars($_POST['nom']);
        $eleve->prenom = htmlspecialchars($_POST['prenom']);
        $eleve->date_naissance = htmlspecialchars($_POST['date_naissance']);
        $eleve->sexe = htmlspecialchars($_POST['sexe']);
        $eleve->matricule = htmlspecialchars($_POST['matricule']);
        $eleve->uname = htmlspecialchars($_POST['uname']);
        $eleve->passwd = htmlspecialchars($_POST['passwd']);
        //$eleve->parent = htmlspecialchars($_POST['parent']);
        $eleve->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Eleve::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>