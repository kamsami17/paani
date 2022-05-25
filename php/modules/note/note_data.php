<?php
  include_once('../../connectdb.php');
  include_once('../../SimpleXLSX.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
  case 'gen_fich':
      $out = shell_exec('python -W ignore ../../../py/gen_fiche_note.py '.$_SESSION['classe']->id.' '.$_SESSION['annee']);
      echo $out;
    break;
  case 'import':
      $code = Note::get_ct($bdd);
      $name = upload($_FILES['fiche_note'], 'notes');
      if ( $xlsx = SimpleXLSX::parse('../../'.$name) ) {
        foreach($xlsx->rows() as $row){
          if(strtolower($row[0])!='num' && strtolower(substr($row[0], 0, 5))!='fiche'){
            if($row[4]!=''){
              $note = new Note();
              $note->matricule = $row[1];
              $note->matiere = htmlspecialchars($_POST['matiere']);
              $note->note = $row[4];
              $note->trimestre = htmlspecialchars($_POST['trimestre']);
              $note->annee = $_SESSION['annee'];
              $note->code = $code;
              $note->create($bdd);

              $r = $bdd->prepare('SELECT e.nom, e.prenom, p.contact FROM eleve e, parent p WHERE p.id=e.parents AND e.matricule=?');
              $r->execute(array($note->matricule));
              $data = $r->fetch(PDO::FETCH_ASSOC);
              $mm = Matiere::get_name($bdd, $note->matiere);
              //send_silent_sms('+226'.$data['contact'], $data['nom'].' '.$data['prenom'].' a eu '.$note->note.' en '.$mm);
            }
          }
        }
      }
      echo 'done';

    break;

	case 'read':
		echo json_encode(Note::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		    $note = new Note();
        $note->matricule = htmlspecialchars($_POST['matricule']);
        $note->matiere = htmlspecialchars($_POST['matiere']);
        $note->note = htmlspecialchars($_POST['note']);
        $note->trimestre = htmlspecialchars($_POST['trimestre']);
        $note->annee = htmlspecialchars($_POST['annee']);
        $note->create($bdd);
		echo 'create';
      break;
    case 'update':
		$note = new Note();
        $note->id = htmlspecialchars($_POST['id']);
        $note->matricule = htmlspecialchars($_POST['matricule']);
        $note->matiere = htmlspecialchars($_POST['matiere']);
        $note->note = htmlspecialchars($_POST['note']);
        $note->trimestre = htmlspecialchars($_POST['trimestre']);
        $note->annee = htmlspecialchars($_POST['annee']);
        $note->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Note::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>