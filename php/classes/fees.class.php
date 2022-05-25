<?php 

class Fees {
        public int $id;
    public int $classe;
    public int $montant;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO fees(classe, montant) VALUES(:classe, :montant)');
  $r->execute(array(
    ':classe' => $this->classe,
    ':montant' => $this->montant));
		$this->id = $bdd->lastInsertId('fees_id_seq');
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM fees  WHERE id=?');
  $r->execute(array($id));
  $el = new Fees();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE fees SET classe=:classe, montant=:montant WHERE id=:id');
  $r->execute(array(
    ':classe' => $this->classe,
   ':montant' => $this->montant,
   ':id' => $this->id)); 
}

public static function get_all($bdd){
	$r = $bdd->query('SELECT * FROM fees');
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Fees();
		$d->hydrate($data);
		array_push($ar, $d);
	}
		return $ar;
}

public static function readclasse($bdd, $classe){
  $r = $bdd->prepare('SELECT * FROM fees WHERE classe=?');
  $r->execute(array($classe));
  $el = new Fees();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM fees WHERE id=?');
  $r->execute(array($id));
}

}

 ?>