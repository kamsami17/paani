<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Activity::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		$activity = new Activity();
        $activity->title = htmlspecialchars($_POST['title']);
        $activity->ecole = htmlspecialchars($_POST['ecole']);
        $activity->description = htmlspecialchars($_POST['description']);
        $activity->img = htmlspecialchars($_POST['img']);
        $activity->date_act = htmlspecialchars($_POST['date_act']);
        $activity->type_act = htmlspecialchars($_POST['type_act']);
        $activity->color = htmlspecialchars($_POST['color']);
        $activity->annee = htmlspecialchars($_POST['annee']);
        $activity->create($bdd);
		echo 'create';
      break;
    case 'update':
		$activity = new Activity();
        $activity->id = htmlspecialchars($_POST['id']);
        $activity->title = htmlspecialchars($_POST['title']);
        $activity->ecole = htmlspecialchars($_POST['ecole']);
        $activity->description = htmlspecialchars($_POST['description']);
        $activity->img = htmlspecialchars($_POST['img']);
        $activity->date_act = htmlspecialchars($_POST['date_act']);
        $activity->type_act = htmlspecialchars($_POST['type_act']);
        $activity->color = htmlspecialchars($_POST['color']);
        $activity->annee = htmlspecialchars($_POST['annee']);
        $activity->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Activity::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>