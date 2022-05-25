<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: token, Content-Type');
  header('Content-Type: application/json');
  include_once("../php/connectdb.php");

  $data = json_decode(file_get_contents('php://input'));

//   $data = json_decode(json_encode([
//     'action'=>'message',
//     // 'mode' => 'classe',
//     'message' => 'hello',
//     'surveillant' => 3,
//     'classe' => 0
//   ]));

  switch ($data->{'action'}) {
    case 'connect':
        $user = new Users();
        $user->email = $data->{'email'};
        $user->passwd = sha1(md5($data->{'passwd'}));
        if($user->auth($bdd)){
            echo json_encode(['success'=> 1, 'user'=> $user]);
        }else{
            echo json_encode(['success'=>0]);
        }
        break;
    
    case 'updatepass':
            $r = $bdd->prepare('UPDATE users SET passwd=? WHERE id=?');
            $r->execute(array(
                $data->newpass,
                $data->user
            ));
            echo json_encode(['success'=>1]);
        break;

    case 'get_classe':
            echo json_encode(Classe::get_all($bdd, $data->{'ecole'}));
        break;

    case 'get_parent_all':
            echo json_encode(Parents::get_all($bdd, $data->{'ecole'}));
        break;

    case 'get_parent_classe':
        $query = 'SELECT e.id, e.nom, e.prenom, e.parents, c.name, c.id as classe, p.nom as pnom, p.prenom as pprenom, p.contact as pcontact FROM eleve e, classe c, classe_eleve ce, parent p WHERE e.id=ce.eleve AND c.id=ce.classe AND p.id=e.parents AND c.id='.$data->{'classe'};
        $r = $bdd->query($query);
        echo json_encode($r->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'add_eleve':
        $eleve = new Eleve();
        $eleve->nom = htmlspecialchars($_POST['nom']);
        $eleve->prenom = htmlspecialchars($_POST['prenom']);
        $eleve->date_naissance = pdate(htmlspecialchars($_POST['date_naissance']));
        $eleve->matricule = htmlspecialchars($_POST['matricule']);
        $eleve->uname = htmlspecialchars($_POST['matricule']);
        $eleve->passwd = htmlspecialchars($_POST['matricule']);
        $eleve->parents = 0;
        $eleve->create($bdd);

        $classe_eleve = new Classe_eleve();
        $classe_eleve->classe = $_SESSION['classe']->id;
        $classe_eleve->eleve = $eleve->id;
        $classe_eleve->annee = $_SESSION['annee']; // change later
        $classe_eleve->create($bdd);
        break;

    case 'send_one_sms':
        if($data->option == 'one'){
            $r = $bdd->prepare('INSERT INTO msg(sender, receiver, message) VALUES(:sender, :receiver, :message)');
            $r->execute(array(
                ':sender' => $data->{'surveillant'},
                ':receiver' => Parents::get_id($bdd, $data->{'contact'}),
                ':message' => $data->{'message'}
            ));
            $status = send_message_sms('+226'.$data->{'contact'}, $data->{'message'}, getmot($bdd, $data->{'ecole'}));
            Message::history($bdd, $data->{'message'}, $data->{'surveillant'},  $data->{'ecole'}, 1, $data->{'contact'});
            if ( $status == 201 or $status == 200 ) {
                echo json_encode(['success' => 1, 'status'=>'working']);
            }
            else echo json_encode(['success' => 0, 'test'=>'efo']);
        }
        else if($data->option == 'all'){
            //$r = $bdd->prepare('SELECT p.id, p.contact FROM parent p, eleve e, classe_eleve ce WHERE ce.eleve=e.id AND p.id=e.parents AND ce.classe=? GROUP BY p.id');
            $r = $bdd->prepare('SELECT id, contact FROM parent WHERE id IN (SELECT e.parents FROM eleve e, classe_eleve ce WHERE ce.eleve=e.id AND ce.classe=:classe)');
            $r->execute(array(
                ':classe' => $data->classe
            ));
            $cname = "Tous les parents";
            $ct = [];
            while($d = $r->fetch(PDO::FETCH_ASSOC)){
                array_push($ct, '+226'.$d['contact']);
            }
            $status = sms_send($ct, $data->message, getmot($bdd, $data->{'ecole'}));
            Message::history($bdd, $data->{'message'}, $data->{'surveillant'},  $data->{'ecole'}, count($ct), $cname);
            if ( $status == 201 or $status == 200 ) {
                echo json_encode(['success' => 1, 'option'=>'all parents '.count($ct)]);
            }
            else echo json_encode(['success' => 0]);
        }
        else{
            $r = $bdd->prepare('SELECT p.id, p.contact FROM parent p, eleve e, classe_eleve ce WHERE ce.eleve=e.id AND p.id=e.parents AND ce.classe=? GROUP BY p.id');
            $r->execute(array($data->{'classe'}));
            $cname = Classe::getname($bdd, $data->{'classe'});
            $ct = [];
            while($d = $r->fetch(PDO::FETCH_ASSOC)){
                array_push($ct, '+226'.$d['contact']);
            }
            $status = sms_send($ct, $data->message, getmot($bdd, $data->{'ecole'}));
            Message::history($bdd, $data->{'message'}, $data->{'surveillant'},  $data->{'ecole'}, count($ct), $cname);
            if ( $status == 201 or $status == 200 ) {
                echo json_encode(['success' => 1]);
            }
            else echo json_encode(['success' => 0]);
        }
        break;

    case 'msg_history':
        $r = $bdd->prepare('SELECT p.nom, p.prenom, p.contact, m.message FROM parent p, msg m WHERE p.id=m.receiver AND m.sender=? LIMIT 10 OFFSET 0 ORDER BY id DESC');
        $r->execute($data->{'surveillant'});
        echo json_encode($r->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'get_trimestre':
        echo json_encode(Trimestre::get_all($bdd, $data->{'classe'}));
        break;

    case 'search':
        // if($data->{'mode'}=='classe')
        //     $query = 'SELECT e.id, e.nom, e.prenom, e.parents, c.name, c.id as classe, (SELECT COUNT(*) FROM discipline WHERE eleve=e.id) as nm, (SELECT concat(nom,\' \',prenom,\' ( \', contact,\' )\') FROM parent WHERE id=e.parents) as pname FROM eleve e, classe c, classe_eleve ce WHERE e.id=ce.eleve AND c.id=ce.classe AND c.id='.$data->{'classe'};
        // else $query = 'SELECT e.id, e.nom, e.prenom, e.parents, c.name, c.id as classe, (SELECT COUNT(*) FROM discipline WHERE eleve=e.id) as nm, (SELECT concat(nom,\' \',prenom,\' ( \', contact,\' )\') FROM parent WHERE id=e.parents) as pname FROM eleve e, classe c, classe_eleve ce WHERE e.id=ce.eleve AND c.id=ce.classe AND '.($data->{'mode'}==='name'? 'LOWER(concat(e.nom, \' \',e.prenom)) LIKE \'%'.$data->{'text'}.'%\'' : 'LOWER(e.matricule) LIKE \'%'.$data->{'text'}.'%\'');
        if($data->{'mode'}=='classe')
            $query = 'SELECT e.id, e.nom, e.prenom, e.parents, c.name, c.id as classe, (SELECT COUNT(*) FROM discipline WHERE eleve=e.id) as nm, p.nom as pnom, p.prenom as pprenom, p.contact as pcontact FROM eleve e, classe c, classe_eleve ce, parent p WHERE e.id=ce.eleve AND c.id=ce.classe AND p.id=e.parents AND c.ecole='.$data->{'ecole'}.' AND c.id='.$data->{'classe'}.' ORDER BY concat(e.nom,e.prenom) ASC';        
        else $query = 'SELECT e.id, e.nom, e.prenom, e.parents, c.name, c.id as classe, (SELECT COUNT(*) FROM discipline WHERE eleve=e.id) as nm, p.nom as pnom, p.prenom as pprenom, p.contact as pcontact FROM eleve e, classe c, classe_eleve ce, parent p WHERE e.id=ce.eleve AND c.id=ce.classe AND p.id=e.parents AND c.ecole='.$data->{'ecole'}.' AND '.($data->{'mode'}==='name'? 'LOWER(concat(e.nom, \' \',e.prenom)) LIKE \'%'.$data->{'text'}.'%\'' : 'LOWER(e.matricule) LIKE \'%'.$data->{'text'}.'%\'').' ORDER BY concat(e.nom,e.prenom) ASC';
        $r = $bdd->query($query);
        echo json_encode($r->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'discipline':
        $d = new Discipline();
        $d->eleve = $data->{'eleve'};
        $d->note = $data->{'note'};
        $d->surveillant = $data->{'surveillant'};
        $d->create($bdd);
        $el = Eleve::get_lib($bdd, $data->eleve);
        $pe = Parents::read($bdd, $data->{'parent'});
        $status = send_message_sms('+226'.$pe->contact, $data->{'note'}, getmot($bdd, $data->{'ecole'}));
        Message::history($bdd, $data->{'note'}, $data->{'surveillant'},  $data->{'ecole'}, 1, $el);
        echo json_encode(['success'=>  $status <= 201 ? 1 : 0]);
        break;
    case 'message':
        $msg = new Message();
        $msg->surveillant = $data->{'surveillant'};
        $msg->message = $data->{'message'};
        $msg->classe = $data->{'classe'};
        $msg->create($bdd);
        
        if($data->{'classe'}!=0){
            $r = $bdd->prepare('SELECT p.id, p.contact FROM parent p, eleve e, classe_eleve ce WHERE ce.eleve=e.id AND p.id=e.parents AND ce.classe=? GROUP BY p.id');
            $r->execute(array($data->{'classe'}));
            $cc = Classe::read($bdd, $data->{'classe'});
        }
        else{
            $r = $bdd->prepare('SELECT id, contact, ecole FROM parent WHERE ecole=?');
            $r->execute(array(Users::get_ecole($bdd, $data->{'surveillant'})));
        }
        $ct = [];
        
        while($d = $r->fetch(PDO::FETCH_ASSOC)){
            //if(strlen($d['contact']) 
            $contact = str_replace('/', '', $d['contact']);
            $contact = str_replace(' ', '', $contact);
            $contact = str_replace('-', '', $contact);
            if(strlen($contact) == 8)
                array_push($ct, '+226'.$contact);
        }
        $cl = $data->classe!=0? $cc->name : "Tous les parents" ;
        Message::history($bdd, $data->{'message'}, $data->{'surveillant'}, $data->{'ecole'}, count($ct), $cl);
        $status = sms_send($ct, $data->message.' #'.$cl, getmot($bdd, $data->{'ecole'}));
        echo json_encode(['success'=>  $status <= 201 ? 1 : 0]);
        break;
  }

?>