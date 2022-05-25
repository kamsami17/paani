<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
	#IF_AUTHENTICATION
	case 'read':
		echo json_encode(Parents::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		    $parent = new Parents();
        $parent->nom = htmlspecialchars($_POST['nom']);
        $parent->prenom = htmlspecialchars($_POST['prenom']);
        $parent->contact = htmlspecialchars($_POST['contact']);
        $parent->email = htmlspecialchars($_POST['email']);
        $parent->passwd = sha1(md5(htmlspecialchars($_POST['contact'])));
        $parent->ecole = $_SESSION['user']->ecole;
        $parent->create($bdd);
		    echo 'create';
      break;

    case 'init_pass':
      $id = htmlspecialchars($_POST['id']);
      $pass = sha1(md5(htmlspecialchars($_POST['contact'])));
      Parents::init_pass($bdd, $id, $pass);
      echo 'done';
      break;

    case 'update':
		$parent = new Parents();
        $parent->id = htmlspecialchars($_POST['id']);
        $parent->nom = htmlspecialchars($_POST['nom']);
        $parent->prenom = htmlspecialchars($_POST['prenom']);
        $parent->contact = htmlspecialchars($_POST['contact']);
        $parent->email = htmlspecialchars($_POST['email']);
        // $parent->passwd = htmlspecialchars($_POST['passwd']);
        $parent->update($bdd);
        echo 'update';
      break;
    case 'delete':
		  Parents::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>