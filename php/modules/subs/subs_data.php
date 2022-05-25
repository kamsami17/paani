<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Subs::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		$subs = new Subs();
        $subs->parent = htmlspecialchars($_POST['parent']);
        $subs->state = htmlspecialchars($_POST['state']);
        $subs->validated_by = htmlspecialchars($_POST['validated_by']);
        $subs->create($bdd);
		echo 'create';
      break;
    case 'update':
		$subs = new Subs();
        $subs->id = htmlspecialchars($_POST['id']);
        $subs->parent = htmlspecialchars($_POST['parent']);
        $subs->state = htmlspecialchars($_POST['state']);
        $subs->validated_by = htmlspecialchars($_POST['validated_by']);
        $subs->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Subs::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>