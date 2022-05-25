<?php 

class Matiere {
        public int $id;
    public string $name;
    public string $code;
    public int $coef;
    public int $classe;


public function hydrate(array $d){
  foreach($d as $key => $value){
    $this->$key = $value;
  }
}

public function create($bdd){
  $r = $bdd->prepare('INSERT INTO matiere(name, code, coef, classe) VALUES(:name, :code, :coef, :classe)');
  $r->execute(array(
    ':name' => $this->name,
    ':code' => $this->code,
    ':coef' => $this->coef,
    ':classe' => $this->classe));
		$this->id = $bdd->lastInsertId('matiere_id_seq');
}

public static function read($bdd, $id){
  $r = $bdd->prepare('SELECT * FROM matiere  WHERE id=?');
  $r->execute(array($id));
  $el = new Matiere();
  $d = $r->fetch(PDO::FETCH_ASSOC);
  $el->hydrate($d);
  return $el;
}

public function update($bdd){
  $r = $bdd->prepare('UPDATE matiere SET name=:name, code=:code, coef=:coef, classe=:classe WHERE id=:id');
  $r->execute(array(
    ':name' => $this->name,
   ':code' => $this->code,
   ':coef' => $this->coef,
   ':classe' => $this->classe,
   ':id' => $this->id)); 
}

public static function get_all($bdd, $classe){
	$r = $bdd->prepare('SELECT * FROM matiere WHERE classe=?');
  $r->execute(array($classe));
	$ar = array();
	while($data = $r->fetch(PDO::FETCH_ASSOC)){
		$d = new Matiere();
		$d->hydrate($data);
		array_push($ar, $d);
	}
  return $ar;
}

public static function get_name($bdd, $id){
  $r = $bdd->prepare('SELECT name FROM matiere WHERE id=?');
  $r->execute(array($id));
  return $r->fetch()['name'];
}

public static function DELETE($bdd, $id){
  $r = $bdd->prepare('DELETE FROM matiere WHERE id=?');
  $r->execute(array($id));
}

}

 ?>