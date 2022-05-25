<?php
  include_once('../../connectdb.php');
  
  switch (htmlspecialchars($_POST['action'])) {
    case 'auth':
      $user = new Users();

      $user->email = htmlspecialchars($_POST['email']);
      $user->passwd = sha1(md5(htmlspecialchars($_POST['passwd'])));
      if($user->auth($bdd)){
        if (session_status() == PHP_SESSION_NONE) {
          session_start();
        }

        $_SESSION['user'] = $user;
        $_SESSION['annee'] = 1; // last annee

        /*if(isset($user->role) && $user->role!=0){
          $role = new Role();
          $role->id = $user->role; $role->read($bdd);
          $_SESSION['role'] = $role;
          $_SESSION['access'] = Access::getRoleAccess($bdd, $role->id);
          $_SESSION['annee'] = Annee::get_last($bdd);
        }*/

        echo 'success';
      }else{
        echo 'error';
      }
    break;

	case 'read':
		echo json_encode(Users::read($bdd, htmlspecialchars($_POST['id'])));
		break;
    case 'create':
		$users = new Users();
        $users->nom = htmlspecialchars($_POST['nom']);
        $users->prenom = htmlspecialchars($_POST['prenom']);
        $users->contact = htmlspecialchars($_POST['contact']);
        $users->email = htmlspecialchars($_POST['email']);
        $users->passwd = sha1(md5(htmlspecialchars($_POST['passwd'])));
        $users->role = htmlspecialchars($_POST['role']);
        $users->ecole = $_SESSION['user']->ecole;
        $users->create($bdd);
		echo 'create';
      break;
    case 'update':
		$users = new Users();
        $users->id = htmlspecialchars($_POST['id']);
        $users->nom = htmlspecialchars($_POST['nom']);
        $users->prenom = htmlspecialchars($_POST['prenom']);
        $users->contact = htmlspecialchars($_POST['contact']);
        $users->email = htmlspecialchars($_POST['email']);
        $users->role = htmlspecialchars($_POST['role']);
        $users->ecole = $_SESSION['user']->ecole;
        $users->update($bdd);
        echo 'update';
      break;
    case 'delete':
		Users::DELETE($bdd, htmlspecialchars($_POST['id']));
      break;

  }
?>