<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: token, Content-Type');
header('Content-Type: application/json');
include_once("../php/connectdb.php");
$data = json_decode(file_get_contents('php://input'));

// $data = json_decode(json_encode([
//     'action'=>'matiere',
//     'classe' => 1
// ]));

switch($data->action){
  case 'get_notes':
        $rmat = $bdd->prepare('SELECT DISTINCT(n.matiere) as mt, m.name, m.code FROM note n, matiere m WHERE m.id=n.matiere AND n.matricule=? AND n.trimestre=?');
        $rmat->execute(array($data->{'matricule'}, $data->{'trimestre'}));
        $ar = [];
        while($d = $rmat->fetch()){
            $r = $bdd->prepare('SELECT note FROM note WHERE matiere=? AND matricule=? AND trimestre=?');
            $r->execute(array($d['mt'], $data->{'matricule'}, $data->{'trimestre'}));
            array_push($ar, ['matiere'=>$d['name'].'('.$d['code'].')', 'notes'=>$r->fetchAll(PDO::FETCH_ASSOC)] );
        }
        echo json_encode($ar);
        break;

    case 'matiere':
        echo json_encode(Matiere::get_all($bdd, $data->classe));
        break;


}


?>
