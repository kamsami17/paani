<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: token, Content-Type');
header('Content-Type: application/json');
include_once("../php/connectdb.php");
$data = json_decode(file_get_contents('php://input'));

// $data = json_decode(json_encode([
//     'action'=>'getClasse',
//     'classe' => 1
// ]));

switch($data->{'action'}){
  case 'getClasse':
      $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
      $ar = array();
      for($i=1; $i<=6; $i++){
        $r = $bdd->prepare('SELECT act, mat, \'\' as code, heure, jour, prof, substring(heure,1,2)::INTEGER as hour FROM timetable WHERE classe=? AND jour=? ORDER BY hour');
        $r->execute(array($data->{'classe'}, $i));
        $d = $r->fetchAll(PDO::FETCH_ASSOC);
        foreach($d as $key => $val){
          if($val['mat']!=0){
            $r0 = $bdd->prepare('SELECT code FROM matiere WHERE id=?');
            $r0->execute(array($val['mat']));
            $d0 = $r0->fetch(PDO::FETCH_ASSOC);
            $d[$key]['code'] = $d0['code'];
          }
        }
        array_push($ar, [ 'day' => $days[$i-1], 'tdata' => $d ]);
        
      }

      echo json_encode(['emp' => $ar]);
    break;


}


 ?>
