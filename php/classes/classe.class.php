<?php 

class Classe {
    public int $id;
    public string $name;
    public int $ecole;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO classe(name, ecole) VALUES(:name, :ecole)');
  $r->execute(array(
    ':name' => $this->name,
    ':ecole' => $this->ecole));
		$this->id = $bdd->lastInsertId('classe_id_seq');
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM classe  WHERE id=?');
  $r->execute(array($id));
  $el = new Classe();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE classe SET name=:name WHERE id=:id');
  $r->execute(array(
    ':name' => $this->name,
   ':id' => $this->id)); 
}

public static function get_all($bdd, $ecole){
	$r = $bdd->prepare('SELECT * FROM classe WHERE ecole=?');
  $r->execute(array($ecole));
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Classe();
		$d->hydrate($data);
		array_push($ar, $d);
	}
  return $ar;
}

public static function getname($bdd, $id){
  $r = $bdd->prepare('SELECT name FROM classe WHERE id=?');
  $r->execute(array($id));
  return $r->fetch()['name'];
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM classe WHERE id=?');
  $r->execute(array($id));
}

}

 ?>