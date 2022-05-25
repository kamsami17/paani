<?php 

class Annee {
        public int $id;
    public string $name;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO annee(name) VALUES(:name)');
  $r->execute(array(
    ':name' => $this->name));
		$this->id = $bdd->lastInsertId('annee_id_seq');
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM annee  WHERE id=?');
  $r->execute(array($id));
  $el = new Annee();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE annee SET name=:name WHERE id=:id');
  $r->execute(array(
    ':name' => $this->name,
   ':id' => $this->id)); 
}

public static function get_all($bdd){
	$r = $bdd->query('SELECT * FROM annee');
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Annee();
		$d->hydrate($data);
		array_push($ar, $d);
	}
		return $ar;
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM annee WHERE id=?');
  $r->execute(array($id));
}

}

 ?>