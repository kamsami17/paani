<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Timetable::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		$timetable = new Timetable();
        $timetable->mat = htmlspecialchars($_POST['mat']);
        $timetable->heure = htmlspecialchars($_POST['heure']);
        $timetable->jour = htmlspecialchars($_POST['jour']);
        $timetable->prof = htmlspecialchars($_POST['prof']);
        $timetable->act = htmlspecialchars($_POST['act']);
        $timetable->classe = htmlspecialchars($_POST['classe']);
        $timetable->annee = htmlspecialchars($_POST['annee']);
        $timetable->create($bdd);
		echo 'create';
      break;
    case 'update':
		$timetable = new Timetable();
        $timetable->id = htmlspecialchars($_POST['id']);
        $timetable->mat = htmlspecialchars($_POST['mat']);
        $timetable->heure = htmlspecialchars($_POST['heure']);
        $timetable->jour = htmlspecialchars($_POST['jour']);
        $timetable->prof = htmlspecialchars($_POST['prof']);
        $timetable->act = htmlspecialchars($_POST['act']);
        $timetable->classe = htmlspecialchars($_POST['classe']);
        $timetable->annee = htmlspecialchars($_POST['annee']);
        $timetable->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Timetable::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>