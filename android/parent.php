<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: token, Content-Type');
  header('Content-Type: application/json');
  include_once("../php/connectdb.php");

  $data = json_decode(file_get_contents('php://input'));

  // $data = json_decode(json_encode([
  //   'action'=>'connect',
  //   'email' => 'laure@mail.com',
  //   'passwd' => '55412121'
  // ]));

  switch ($data->{'action'}) {
    case 'connect':
        $annee = current_annee($bdd);
        $r = $bdd->prepare('SELECT * FROM parent WHERE email=:email AND passwd=:passwd');
        $r->execute(array(
          ':email'=>$data->{'email'},
          ':passwd'=> sha1(md5($data->{'passwd'}))
        ));
        if($r->rowCount()==1){
          $d = $r->fetch(PDO::FETCH_ASSOC);
          $enfants = Parents::get_enfants($bdd, $d['id'], $annee);

        //   $rsub = $bdd->prepare('SELECT * FROM subs WHERE parent=? AND annee=?');
        //   $rsub->execute(array($d['id'], $annee));

          echo json_encode([
            'parent'=>$d, 
            'enfants'=>$enfants, 
            'success'=>1
            //'subs'=>$rsub->rowCount()
          ]);
        }else{
          echo json_encode(['success'=>0]);
        }

      break;
    case 'checkSub':
        $annee = current_annee($bdd);
        $rsub = $bdd->prepare('SELECT * FROM subs WHERE parent=? AND annee=?');
        $rsub->execute(array($data->{'id'}, $annee));
        echo json_encode(['subs'=>$rsub->rowCount()]);
      break;
    case 'pwd':
        $r0 = $bdd->prepare('SELECT code FROM parent WHERE id=:id');
        $r0->execute(array(':id'=>$data->id));
        $d0 = $r0->fetch();
        if($d0['code']==$data->{'oldpass'}){
          $r = $bdd->prepare('UPDATE parent SET code=:newpass WHERE id=:id');
          $r->execute(array(':newpass' => $data->{'newpass'},
            ':id' => $data->{'id'}
          ));
          echo json_encode(['success'=>1]);
        }
        else{
          echo json_encode(['success'=>0]);
        }
      break;
    case 'discipline':
        echo json_encode(Discipline::get_eleve($bdd, $data->{'eleve'}));
      break;

    case 'message':
      //$data
      break;

    case 'demande_sous':
      break;

    default:
      // code...
      break;
  }

 ?>
