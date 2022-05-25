<?php

class Timetable{
 public
$id, $mat, $heure, $jour, $prof, $act, $classe, $annee;

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
  $re = $bdd->prepare('INSERT INTO timetable(mat,heure,jour,prof, act, classe, annee) VALUES(:mat,:heure,:jour,:prof, :act, :classe, :annee)');
  $re->execute(array(':mat' => $this->mat,
':heure' => $this->heure,
':jour' => $this->jour,
':prof' => $this->prof,
':act' => $this->act,
':annee' => $this->annee,
':classe' => $this->classe  ));
}

public function getMat($bdd){
  $r = $bdd->prepare('SELECT name FROM matiere WHERE id=?');
  $r->execute(array($this->mat));
  $d = $r->fetch();
  return $d['name'];
}
//Update
  public function update($bdd){    $re=$bdd->prepare('UPDATE timetable SET mat=:mat,heure=:heure,jour=:jour,prof=:prof, act=:act WHERE id=:id');
  $re->execute(array(':id' => $this->id,
':mat' => $this->mat,
':heure' => $this->heure,
':jour' => $this->jour,
':prof' => $this->prof,
':act' => $this->act ));
}

//Read
  public function read($bdd){    $re = $bdd->prepare('SELECT * FROM timetable WHERE id=:id');
    $re->execute(array(':id' => $this->id));
 $d = $re->fetch();
 $this->hydrate($d);}


//Read
  public static function getAll($bdd){    $re = $bdd->query('SELECT * FROM timetable');
   $ar = array();
   while($data = $re->fetch()){
       $obj = new Timetable();
       $obj->hydrate($data);
       array_push($ar, $obj);
   }
    return $ar;
 }

 public static function getDay($bdd, $jour, $classe, $annee){
   $re = $bdd->prepare('SELECT id, mat, heure, jour, prof, substring(heure,1,2)::INTEGER as hour, act FROM timetable WHERE jour=? AND classe=? AND annee=? ORDER BY hour');
   $re->execute(array($jour, $classe, $annee));
   $ar = array();
   while($data = $re->fetch()){
      $obj = new Timetable();
      $obj->hydrate($data);
      array_push($ar, $obj);
   }
   return $ar;
 }

  //DELETE
  public static function DELETE($bdd, $id){    $re = $bdd->prepare('DELETE FROM timetable WHERE id=:id');
    $re->execute(array(':id' => $id));
  }

}


 ?>
