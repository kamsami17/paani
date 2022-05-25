<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: token, Content-Type');
  header('Content-Type: application/json');
  include_once("../php/connectdb.php");
  $data = json_decode(file_get_contents('php://input'));
  switch($data->{'action'}){
    case 'getAll':
        $annee = current_annee($bdd);
        $r = $bdd->prepare('SELECT a.id, a.title,a.ecole, a.description, a.img, a.date_act,a.type_act, a.color FROM activity a WHERE a.annee=:annee AND a.ecole=:ecole AND ((a.id NOT IN (SELECT activity FROM cact)) OR (a.id IN (SELECT activity FROM cact WHERE classe=:classe))) ORDER BY a.date_act DESC');
        $r->execute(array(
          ':ecole'=>1,
          ':classe'=>$data->{'classe'},
          ':annee'=>$annee
        ));
        $d0 = $r->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['acts'=>$d0]);
      break;
  }

?>
