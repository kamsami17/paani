<?php
class Activity{
public $id, $title, $classe, $ecole, $description, $img, $date_act, $type_act, $color, $annee;

//Constructor
public function __construct(){}

public function hydrate(array $d)
{
  foreach ($d as $key => $value){
    $this->$key = $value;
  }
}

//============= CRUD ===================
//Create
public function create($bdd){
$re = $bdd->prepare('INSERT INTO activity(title,ecole,description,img,date_act, type_act, color, annee) VALUES(:title,:ecole,:description,:img, :date_act, :type_act, :color, :annee)');
$re->execute(array(':title' => $this->title,
':ecole' => $this->ecole,
':description' => $this->description,
':img' => $this->img,
':date_act'=>$this->date_act,
':type_act'=>$this->type_act,
':color'=>$this->color,
':annee'=>$this->annee  ));
$this->id = $bdd->lastInsertId('activity_id_seq');
}

//Update
public function update($bdd){    $re=$bdd->prepare('UPDATE activity SET title=:title,ecole=:ecole,description=:description,img=:img, date_act=:date_act WHERE id=:id');
  $re->execute(array(':id' => $this->id,
  ':title' => $this->title,
  ':ecole' => $this->ecole,
  ':description' => $this->description,
  ':img' => $this->img,
  ':date_act'=>$this->date_act));

  $r = $bdd->prepare('DELETE FROM cact WHERE activity=?');
  $r->execute(array($this->id));
}

//Read
public function read($bdd){    $re = $bdd->prepare('SELECT * FROM activity WHERE id=?');
  $re->execute(array( $this->id ));
$d = $re->fetch();
$this->hydrate($d);

}

public static function getAll($bdd){    $re = $bdd->query('SELECT * FROM activity');
 $ar = array();
 while($data = $re->fetch()){
     $obj = new Activity();
     $obj->hydrate($data);
     array_push($data, $ar);
 }
  return $ar;
}
public static function getConcern($bdd, $id){
  $r = $bdd->prepare('SELECT c.name FROM cact ca, classe c WHERE ca.activity=:act AND ca.classe=c.id');
  $r->execute(array(':act'=>$id));
  $res=$r->rowCount()==0? 'Toute l\'école': '';
  while($d=$r->fetch()){
    $res = $res.$d['nom'].', ';
  }
  return $res;
}

public static function show_concern($bdd, array $ar){
  $result = 'Classe(s): ';
  if(count($ar)==0){
    return 'Toute l\'école';
  }
  foreach ($ar as $key => $value) {
    $r = $bdd->prepare('SELECT nom FROM classe WHERE id=?');
    $r->execute(array($value));
    $d = $r->fetch();
    $result .= $d['nom'].', ';
  }
  return $result;
}

public static function DELETE($bdd, $id){
  $re = $bdd->prepare('DELETE FROM activity WHERE id=?');
  $re->execute(array($id));

  $r = $bdd->prepare('DELETE FROM cact WHERE activity=?');
  $r->execute(array($id));
}

}



 ?>
