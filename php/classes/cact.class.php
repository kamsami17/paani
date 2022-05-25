<?php 

class Cact {
  public $id, $classe, $activity;

public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO cact(classe, activity) VALUES(:classe, :activity)');
  $r->execute(array(
    ':classe' => $this->classe,
    ':activity' => $this->activity));
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM cact  WHERE id=?');
  $r->execute(array($id));
  $el = new Cact();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE cact SET classe=:classe, activity=:activity WHERE id=:id');
  $r->execute(array(
    ':classe' => $this->classe,
   ':activity' => $this->activity,
   ':id' => $this->id)); 
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM cact WHERE id=?');
  $r->execute(array($id));
}

}

 ?>